<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\UserDetails;
use App\Models\UserPhones;
use App\Models\UserSocialNetworks;
use App\User;
use Illuminate\Http\Request;

class ApiUserController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page');
        $size = $request->get('size');
        $sort = explode(',', $request->get('sort'));

        if (!$page) {
            $page = 1;
        }

        if (!$size) {
            $size = 10;
        }

        $flag = false;
        if (is_array($sort) and count($sort) > 1) {
            $object = new User();
            if (in_array($sort[0], $object->getFields()) && in_array($sort[1], array('desc', 'asc'))) {
                $flag = true;
            }
        }

        if ($flag) {
            $users = User::offset($page * $size)->orderBy($sort[0], $sort[1])->paginate($size);
        } else {
            $users = User::offset($page * $size)->orderBy("created_at", 'desc')->paginate($size);
        }

        if (!empty($users)) {
            foreach ($users as $user) {
                $user->count_cards_not_archived = Card::where('is_archived', '=', '0')->where('user_id', '=', $user->id)->count();
                if ($user->UserDetails) {
                    $user->UserDetails->profileImage;
                }
                $user->UserPhones;
                $user->UserSocials;
            }
        }

        return $users;
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
        if ($user->UserDetails) {
            $user->UserDetails->profileImage;
        }
        $user->UserPhones;
        $user->UserSocials;
        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(array('error' => array('status' => 401, 'message' => 'Unauthorized. The user needs to be authenticated.')), 401);
        }

        if ($user->is_archived === 1) {
            return response()->json(array('error' => array('status' => 401, 'message' => 'User is deleted')), 401);
        }

        $user = User::find($id);
        if ($user) {
            $data = $request->all();
            if (isset($data['is_archived'])) {
                unset($data['is_archived']);
            }
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
                } else {
                    UserDetails::create([
                        'user_id' => $user->id,
                        'city' => (isset($user_details_data['city']) ? $user_details_data['city'] : ''),
                        'postal_code' => (isset($user_details_data['postal_code']) ? $user_details_data['postal_code'] : ''),
                        'profile_image_id' => (isset($user_details_data['profile_image_id']) ? $user_details_data['profile_image_id'] : ''),
                        'birthday' => (isset($user_details_data['birthday']) ? $user_details_data['birthday'] : ''),
                        'currency' => (isset($user_details_data['currency']) ? $user_details_data['currency'] : '')
                    ]);
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
        if ($user->UserDetails) {
            $user->UserDetails->profileImage;
        }
        $user->UserPhones;
        $user->UserSocials;

        return response()->json($user, 200);
    }

    public function delete(Request $request, $id) {

        $user = $request->user();

        if (!$user) {
            return response()->json(array('error' => array('status' => 401, 'message' => 'Unauthorized. The user needs to be authenticated.')), 401);
        }

        if ($user->is_archived === 1) {
            return response()->json(array('error' => array('status' => 401, 'message' => 'User is deleted')), 401);
        }

        $user = User::find($id);

        if ($user) {
            $user->is_archived = 1;
            $user->save();
        }

        return response()->json(null, 204);
    }

}
