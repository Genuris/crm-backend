<?php

namespace App\Http\Controllers;

use App\Models\CardContactsPhones;
use App\Models\UserPhones;
use Illuminate\Http\Request;

class ApiUserCardController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $user = $request->user();

            if (!$user) {
                return response()->json(array('error' => array('status' => 401, 'message' => 'Unauthorized. The user needs to be authenticated.')), 401);
            }

            if ($user->is_archived === 1) {
                return response()->json(array('error' => array('status' => 401, 'message' => 'User is deleted')), 401);
            }

            return $next($request);
        });
    }

    public function getContactUserByPhones(Request $request)
    {
        $phone = $request->get('phone');

        $current_user = $request->user();
        $agency_id = null;
        if ($current_user) {
            $agency_id = $current_user->agency_id;
        }

        $user_phones = UserPhones::where('value', 'like', '%' . $phone . '%')->get();
        $user_phones_data = array();

        if (!empty($user_phones)) {
            foreach ($user_phones as $user_phone) {
                $user = $user_phone->user;
                if ($user) {
                    if (!is_null($agency_id) && $user->agency_id == $agency_id) {
                        $user->UserDetails;
                        if ($user->UserDetails) {
                            $user->UserDetails->profileImage;
                        }
                        $user->UserPhones;
                        $user->UserSocials;
                        $user_phones_data[] = $user;
                    } else if (is_null($agency_id)) {
                        $user->UserDetails;
                        if ($user->UserDetails) {
                            $user->UserDetails->profileImage;
                        }
                        $user->UserPhones;
                        $user->UserSocials;
                        $user_phones_data[] = $user;
                    }
                }

            }
        }

        if (is_null($agency_id)) {
            $card_contacts_phones = CardContactsPhones::where('phone', '=', $phone)->get();
        } else {
            $card_contacts_phones = CardContactsPhones::where('phone', 'like', '%' . $phone . '%')->where('agency_id', '=', $agency_id)->get();
        }

        $card_contacts_phones_data = array();

        if (!empty($card_contacts_phones)) {
            foreach ($card_contacts_phones as $phone) {
                $card_contacts = $phone->contact;
                if ($card_contacts) {
                    $card_contacts->CardsContactsPhones;
                    $card_contacts_phones_data[] = $card_contacts;
                }
            }
        }

        return response()->json(array(
            'users' => $user_phones_data,
            'contacts' => $card_contacts_phones_data
        ), 200);
    }

}
