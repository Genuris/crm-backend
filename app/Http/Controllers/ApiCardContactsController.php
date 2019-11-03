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
        'post' => ['add' => ['api/card_contacts']],
    );

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

            $role = Role::find($user->role_id);

            if (!$role) {
                return response()->json(array('error' => array('status' => 401, 'message' => 'Unauthorized. The user needs to be authenticated.')), 401);
            }

            if (!$role->checkAction($request->path(), $request->method(), $this->permissions, new Role())) {
                return response()->json(array('error' => array('status' => 403, 'message' => 'Forbidden. The user is authenticated, but does not have the permissions to perform an action.')), 403);
            }

            return $next($request);
        });
    }

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
            $object = new CardContacts();
            if (in_array($sort[0], $object->getFields()) && in_array($sort[1], array('desc', 'asc'))) {
                $flag = true;
            }
        }

        if ($flag) {
            $contacts = CardContacts::offset($page * $size)->orderBy($sort[0], $sort[1])->paginate($size);
        } else {
            $contacts = CardContacts::offset($page * $size)->orderBy("created_at", 'desc')->paginate($size);
        }

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

        if ($card_contacts) {
            $card_contacts->CardsContactsPhones;
        }

        return response()->json($card_contacts, 200);
    }

    public function store(Request $request)
    {
        $card_contact_data = $request->all();
        $cards_contacts_id = null;

        if (isset($card_contact_data['cards_contacts_phones']) and is_array($card_contact_data['cards_contacts_phones'])) {

            foreach ($card_contact_data['cards_contacts_phones'] as $key => $value) {

                $card_contacts_phone = CardContactsPhones::where('phone', 'like', $value['phone'])->where('agency_id', '=', $request->get('agency_id'))->first();

                if ($card_contacts_phone) {

                    $cards_contacts_id = $card_contacts_phone->cards_contacts_id;
                    break;

                }

            }

        } else {
            return response()->json(array(), 402);
        }

        if (!$cards_contacts_id) {

            $card_contact = CardContacts::create([
                'name' => (isset($card_contact_data['name']) ? $card_contact_data['name'] : ''),
                'email' => (isset($card_contact_data['email']) ? $card_contact_data['email'] : ''),
                'children' => (isset($card_contact_data['children']) ? $card_contact_data['children'] : 0),
                'car' => (isset($card_contact_data['car']) ? $card_contact_data['car'] : ''),
                'work_place' => (isset($card_contact_data['work_place']) ? $card_contact_data['work_place'] : ''),
                'is_married' => (isset($card_contact_data['is_married']) ? $card_contact_data['is_married'] : ''),
                'is_client' => (isset($card_contact_data['is_client']) ? $card_contact_data['is_client'] : ''),
                'is_partner' => (isset($card_contact_data['is_partner']) ? $card_contact_data['is_partner'] : ''),
                'is_realtor' => (isset($card_contact_data['is_realtor']) ? $card_contact_data['is_realtor'] : ''),
                'years' => (isset($card_contact_data['years']) ? $card_contact_data['years'] : ''),
                'leisure' => (isset($card_contact_data['leisure']) ? $card_contact_data['leisure'] : ''),
                'kind_of_activity' => (isset($card_contact_data['kind_of_activity']) ? $card_contact_data['kind_of_activity'] : ''),
                'animals' => (isset($card_contact_data['animals']) ? $card_contact_data['animals'] : ''),
                'decision_makers' => (isset($card_contact_data['decision_makers']) ? $card_contact_data['decision_makers'] : ''),
                'is_black_list' => (isset($card_contact_data['is_black_list']) ? $card_contact_data['is_black_list'] : ''),
                'agency_id' => $request->get('agency_id')
            ]);

            if (!$card_contact) {
                return response()->json(array(), 402);
            }

            $cards_contacts_id = $card_contact->id;

            foreach ($card_contact_data['cards_contacts_phones'] as $key => $value) {

                CardContactsPhones::create([
                    'cards_contacts_id' => $cards_contacts_id,
                    'phone' => (isset($value['phone']) ? $value['phone'] : ''),
                    'agency_id' => $request->get('agency_id')
                ]);

            }

            if ($card_contact) {
                $card_contact->CardsContactsPhones;
            }

            return response()->json($card_contact, 201);
        }
        return response()->json(array(), 402);
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
        if ($card_contacts) {
            $card_contacts->CardsContactsPhones;
        }

        return response()->json($card_contacts, 200);
    }
}
