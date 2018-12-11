<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\SocialNetworks;
use App\Models\UserDetails;
use App\Models\UserPhones;
use App\Models\UserSocialNetworks;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiRegisterController extends Controller
{
    public function store(Request $request)
    {
//        dd(Auth::check());
        //dd(Auth::guard('api')->user()->role_id);
        if (empty($request->get('name')) || is_null($request->get('name'))) {
            return response()->json(array('error' => array('status' => 400, 'message' => 'name is empty')), 400);
        }

        if (empty($request->get('email')) || is_null($request->get('email'))) {
            return response()->json(array('error' => array('status' => 400, 'message' => 'email is empty')), 400);
        }

        if (empty($request->get('password')) || is_null($request->get('password'))) {
            return response()->json(array('error' => array('status' => 400, 'message' => 'password is empty')), 400);
        }

        if (empty($request->get('role_id')) || is_null($request->get('role_id'))) {
            return response()->json(array('error' => array('status' => 400, 'message' => 'role_id is empty')), 400);
        }

        if (empty($request->get('surname')) || is_null($request->get('surname'))) {
            return response()->json(array('error' => array('status' => 400, 'message' => 'surname is empty')), 400);
        }

        if (empty($request->get('middle_name')) || is_null($request->get('middle_name'))) {
            return response()->json(array('error' => array('status' => 400, 'message' => 'middle_name is empty')), 400);
        }

        $role = Role::find($request->get('role_id'));

        if (!$role) {
            return response()->json(array('error' => array('status' => 400, 'message' => 'role is missing')), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'role_id' => $request->get('role_id'),
            'password' => bcrypt($request->get('password')),
            'middle_name' => $request->get('middle_name'),
            'time_zone' => $request->get('time_zone'),
            'surname' => $request->get('surname'),
        ]);

        if ($user) {

            if (!empty($request->get('user_details')) && is_array($request->get('user_details'))) {

                $user_details_data = $request->get('user_details');
                UserDetails::create([
                    'user_id' => $user->id,
                    'city' => (isset($user_details_data['city']) ? $user_details_data['city'] : ''),
                    'postal_code' => (isset($user_details_data['postal_code']) ? $user_details_data['postal_code'] : ''),
                    'profile_image_id' => (isset($user_details_data['profile_image_id']) ? $user_details_data['profile_image_id'] : ''),
                    'currency' => (isset($user_details_data['currency']) ? $user_details_data['currency'] : '')
                ]);

            }

            if (!empty($request->get('user_phones')) && is_array($request->get('user_phones'))) {

                $user_phones_data = $request->get('user_phones');

                if (is_array($user_phones_data) and !empty($user_phones_data)) {

                    foreach ($user_phones_data as $user_phone_data) {

                        UserPhones::create([
                            'user_id' => $user->id,
                            'value' => (isset($user_phone_data['value']) ? $user_phone_data['value'] : '')
                        ]);

                    }

                }
            }

            if (!empty($request->get('user_socials')) && is_array($request->get('user_socials'))) {

                $user_socials_data = $request->get('user_socials');

                if (is_array($user_socials_data) and !empty($user_socials_data)) {

                    foreach ($user_socials_data as $social_network_id => $user_social_data) {

                        $social_network = SocialNetworks::find($social_network_id);

                        if ($social_network) {
                            UserSocialNetworks::create([
                                'user_id' => $user->id,
                                'social_network_id' => $social_network_id,
                                'value' => (isset($user_social_data['value']) ? $user_social_data['value'] : '')
                            ]);
                        }

                    }

                }
            }

        }

//        $user_result = (array)$user;
//        $user_result['user_details'] = $user->UserDetails();
//        $user_result['user_phones'] = $user->UserPhones();
//        $user_result['user_socials'] = $user->UserSocials();

        return response()->json($user, 201);
    }
}
