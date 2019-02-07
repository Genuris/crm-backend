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
        if (!$user) {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }
        $user->UserDetails;
        if ($user->UserDetails) {
            $user->UserDetails->profileImage;
        }
        $user->UserPhones;
        $user->UserSocials;
        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
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

            if (is_array($request->get('user_phones'))) {

                $user_phones_data = $request->get('user_phones');

                $old_data = $user->UserPhones;
                $old_data_array = array();

                if (!empty($old_data)) {
                    foreach ($old_data as $obj) {
                        $old_data_array[$obj->id] = $obj->id;
                    }
                }

                if (is_array($user_phones_data) and !empty($user_phones_data)) {
                    foreach ($user_phones_data as $user_phone_data) {
                        if (isset($user_phone_data['id'])) {
                            $user_phone = UserPhones::find($user_phone_data['id']);
                            if ($user_phone) {
                                $user_phone->value = $user_phone_data['value'];
                                $user_phone->save();
                                unset($old_data_array[$user_phone_data['id']]);
                            } else {
                                UserPhones::create([
                                    'user_id' => $user->id,
                                    'value' => (isset($user_phone_data['value']) ? $user_phone_data['value'] : '')
                                ]);
                            }
                        } else {
                            UserPhones::create([
                                'user_id' => $user->id,
                                'value' => (isset($user_phone_data['value']) ? $user_phone_data['value'] : '')
                            ]);
                        }
                    }
                }
                if (!empty($old_data_array)) {
                    foreach ($old_data_array as $old_data) {
                        if ($old_data) {
                            UserPhones::where('id', '=', $old_data)->delete();
                        }
                    }
                }
            }

            if (is_array($request->get('user_socials'))) {

                $user_socials_data = $request->get('user_socials');

                $old_data = $user->UserSocials;
                $old_data_array = array();

                if (!empty($old_data)) {
                    foreach ($old_data as $obj) {
                        $old_data_array[$obj->id] = $obj->id;
                    }
                }

                if (is_array($user_socials_data) and !empty($user_socials_data)) {
                    foreach ($user_socials_data as $user_social_data) {

                        if (isset($user_social_data['id'])) {
                            $user_social = UserSocialNetworks::find($user_social_data['id']);
                            if ($user_social) {
                                $user_social->value = $user_social_data['value'];
                                $user_social->save();
                                unset($old_data_array[$user_social_data['id']]);
                            } else {
                                UserSocialNetworks::create([
                                    'user_id' => $user->id,
                                    'social_network_id' => $user_social_data['social_network_id'],
                                    'value' => (isset($user_social_data['value']) ? $user_social_data['value'] : '')
                                ]);
                            }
                        } else {
                            UserSocialNetworks::create([
                                'user_id' => $user->id,
                                'social_network_id' => $user_social_data['social_network_id'],
                                'value' => (isset($user_social_data['value']) ? $user_social_data['value'] : '')
                            ]);
                        }

                    }

                }
                if (!empty($old_data_array)) {
                    foreach ($old_data_array as $old_data) {
                        if ($old_data) {
                            UserSocialNetworks::where('id', '=', $old_data)->delete();
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

        $user = User::find($id);
        $user->UserDetails;
        if ($user->UserDetails) {
            $user->UserDetails->profileImage;
        }
        $user->UserPhones;
        $user->UserSocials;

        return response()->json($user, 200);
    }

    public function delete(Request $request, $id) {
        $user = User::find($id);

        if ($user) {
            $user->delete();
        }

        return response()->json(null, 204);
    }

}
