<?php

namespace App\Http\Controllers;

use App\Models\CardContactsPhones;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\CardContacts;

class ApiCardContactsController extends Controller
{
    public $permissions = array(
        'GET' => ['see' => ['api/card_contacts']],
        'PUT' => ['edit' => ['api/card_contacts']],
        'DELETE' => ['delete' => ['api/card_contacts']],
    );

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $user = $request->user();

            if (!$user) {
                return response()->json(array('error' => array('status' => 401, 'message' => 'Unauthorized. The user needs to be authenticated.')), 401);
            }

            $card_contacts = Role::find($user->role_id);

            if (!$card_contacts) {
                return response()->json(array('error' => array('status' => 401, 'message' => 'Unauthorized. The user needs to be authenticated.')), 401);
            }

            if (!$card_contacts->checkAction($request->path(), $request->method(), $this->permissions, new Role())) {
                return response()->json(array('error' => array('status' => 403, 'message' => 'Forbidden. The user is authenticated, but does not have the permissions to perform an action.')), 403);
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $page = $request->get('page');
        $size = $request->get('size');

        if (!$page) {
            $page = 1;
        }

        if (!$size) {
            $size = 10;
        }

        $contacts = CardContacts::offset($page * $size)->orderBy("created_at", 'desc')->paginate($size);

        if (!empty($contacts)) {
            foreach ($contacts as $contact) {
                $contact->CardsContactsPhones;
            }
        }
        return $contacts;
    }

    public function show($id)
    {
        $card_contacts = CardContacts::find($id);

        if($card_contacts) {
            $card_contacts->CardsContactsPhones;
        }

        return response()->json($card_contacts, 200);
    }

    public function update(Request $request, $id)
    {
        $card_contacts = CardContacts::find($id);
        if (!$card_contacts) {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }
        $card_contacts->update($request->all());

        if (is_array($request->get('cards_contacts_phones'))) {

            $card_contact_phones_data = $request->get('cards_contacts_phones');

            $old_data = $card_contacts->CardsContactsPhones;

            $old_data_array = array();

            if (!empty($old_data)) {
                foreach ($old_data as $obj) {
                    $old_data_array[$obj->id] = $obj->id;
                }
            }

            if (is_array($card_contact_phones_data) and !empty($card_contact_phones_data)) {
                foreach ($card_contact_phones_data as $card_contact_phone_data) {

                    if (isset($card_contact_phone_data['id'])) {
                        $user_phone = CardContactsPhones::find($card_contact_phone_data['id']);

                        if ($user_phone) {
                            if (isset($card_contact_phone_data['phone'])) {
                                $user_phone->phone = $card_contact_phone_data['phone'];
                                $user_phone->save();
                            }
                            unset($old_data_array[$card_contact_phone_data['id']]);
                        } else {
                            CardContactsPhones::create([
                                'cards_contacts_id' => $card_contacts->id,
                                'agency_id' => $card_contacts->agency_id,
                                'phone' => (isset($card_contact_phone_data['phone']) ? $card_contact_phone_data['phone'] : '')
                            ]);
                        }
                    } else {
                        if (isset($card_contact_phone_data['phone'])) {
                            CardContactsPhones::create([
                                'cards_contacts_id' => $card_contacts->id,
                                'agency_id' => $card_contacts->agency_id,
                                'phone' => $card_contact_phone_data['phone']
                            ]);
                        }
                    }
                }
            }
            if (!empty($old_data_array)) {
                foreach ($old_data_array as $old_data) {
                    if ($old_data) {
                        CardContactsPhones::where('id', '=', $old_data)->delete();
                    }
                }
            }
        }

        $card_contacts = CardContacts::find($id);
        if($card_contacts) {
            $card_contacts->CardsContactsPhones;
        }

        return response()->json($card_contacts, 200);
    }
}
