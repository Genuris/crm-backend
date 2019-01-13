<?php

namespace App\Http\Controllers;

use App\Models\SocialNetworks;
use App\Models\UserDetails;
use App\Models\UserPhones;
use App\Models\UserSocialNetworks;
use App\User;
use Illuminate\Http\Request;

class ApiUserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function show($id)
    {
        $user = User::find($id);
        $user->UserDetails;
        $user->UserPhones;
        $user->UserSocials;
        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user) {
            $data = $request->all();
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }
            $user->update($data);

            if (!empty($request->get('user_details')) && is_array($request->get('user_details'))) {

                $user_details_data = $request->get('user_details');

                $user_details = UserDetails::where('user_id', '=', $user->id)->first();
                if ($user_details) {

                    if (isset($user_details_data['city']) && $user_details_data['city']) {
                        $user_details->city = $user_details_data['city'];
                    }

                    if (isset($user_details_data['postal_code']) && $user_details_data['postal_code']) {
                        $user_details->postal_code = $user_details_data['postal_code'];
                    }

                    if (isset($user_details_data['profile_image_id']) && $user_details_data['profile_image_id']) {
                        $user_details->profile_image_id = $user_details_data['profile_image_id'];
                    }

                    if (isset($user_details_data['birthday']) && $user_details_data['birthday']) {
                        $user_details->birthday = $user_details_data['birthday'];
                    }

                    if (isset($user_details_data['currency']) && $user_details_data['currency']) {
                        $user_details->currency = $user_details_data['currency'];
                    }

                    $user_details->save();
                }

            }

            if (!empty($request->get('user_phones')) && is_array($request->get('user_phones'))) {

                $user_phones_data = $request->get('user_phones');

                if (is_array($user_phones_data) and !empty($user_phones_data)) {

                    foreach ($user_phones_data as $user_phone_id => $user_phone_data) {

                        $user_phone = UserPhones::find($user_phone_id);
                        if ($user_phone) {
                            $user_phone->value = $user_phone_data;
                            $user_phone->save();
                        } else {
                            UserPhones::create([
                                'user_id' => $user->id,
                                'value' => (isset($user_phone_data['value']) ? $user_phone_data['value'] : '')
                            ]);
                        }

                    }

                }
            }

            if (!empty($request->get('user_socials')) && is_array($request->get('user_socials'))) {

                $user_socials_data = $request->get('user_socials');

                if (is_array($user_socials_data) and !empty($user_socials_data)) {

                    foreach ($user_socials_data as $social_network_id => $user_social_data) {

                        $social_network = SocialNetworks::find($social_network_id);

                        if ($social_network) {
                            $user_social = UserSocialNetworks::where('user_id', '=', $user->id)->andWhere('social_network_id', '=', $social_network_id)->first();
                            if ($user_social) {
                                $user_social->value = $user_social_data;
                                $user_social->save();
                            }

                        } else {
                            UserSocialNetworks::create([
                                'user_id' => $user->id,
                                'social_network_id' => $social_network_id,
                                'value' => (isset($user_social_data['value']) ? $user_social_data['value'] : '')
                            ]);
                        }

                    }

                }
            }

        } else {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }
        $user->UserDetails;
        $user->UserPhones;
        $user->UserSocials;

        return response()->json($user, 200);
    }
}
