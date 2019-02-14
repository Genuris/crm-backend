<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardContacts;

class ApiCardContactsController extends Controller
{
    public $permissions = array(
        'GET' => ['see' => ['api/card_contacts']],
        'PUT' => ['update' => ['api/card_contacts']],
        'DELETE' => ['delete' => ['api/card_contacts']],
    );

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            /*$user = $request->user();

            if (!$user) {
                return response()->json(array('error' => array('status' => 401, 'message' => 'Unauthorized. The user needs to be authenticated.')), 401);
            }

            $card_contacts = Role::find($user->role_id);

            if (!$card_contacts) {
                return response()->json(array('error' => array('status' => 401, 'message' => 'Unauthorized. The user needs to be authenticated.')), 401);
            }

            if (!$card_contacts->checkAction($request->path(), $request->method(), $this->permissions, new Role())) {
                return response()->json(array('error' => array('status' => 403, 'message' => 'Forbidden. The user is authenticated, but does not have the permissions to perform an action.')), 403);
            }*/

            return $next($request);
        });
    }

    public function index()
    {
        $contacts = CardContacts::all();

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

        $card_contacts = CardContacts::find($id);
        if($card_contacts) {
            $card_contacts->CardsContactsPhones;
        }

        return response()->json($card_contacts, 200);
    }
}
