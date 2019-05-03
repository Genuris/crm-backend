<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\CardsFile;
use App\Models\CardContacts;
use App\Models\CardContactsPhones;

class ApiCardsController extends Controller
{

    public $permissions = array(
        'GET' => ['see' => ['api/cards']],
        'PUT' => ['edit' => ['api/cards', 'api/cards_contact_black_list']],
        'POST' => ['add' => ['api/cards', 'api/cards_contact_phone', 'api/cards_filtered']],
        'DELETE' => ['delete' => ['api/cards', 'api/cards_delete', 'api/cards_contact_delete']],
    );

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $user = $request->user();

            if (!$user) {
                return response()->json(array('error' => array('status' => 401, 'message' => 'Unauthorized. The user needs to be authenticated.')), 401);
            }

            $role = Role::find($user->role_id);

            if (!$role) {
                return response()->json(array('error' => array('status' => 401, 'message' => 'Unauthorized. The user needs to be authenticated.')), 401);
            }

            if (!$role->checkAction($request->path(), $request->method(), $this->permissions, new Card())) {
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

        $cards = Card::offset($page * $size)->orderBy("created_at", 'desc')->paginate($size);

        if (!empty($cards)) {
            foreach ($cards as $card) {
                $card->CardContact;
                if (!empty($card->CardContact)) {
                    $card->CardContact->CardsContactsPhones;
                }

                $card->CardFiles;
                $card->CardAgency;
                $card->CardOffice;
                $card->CardUser;

                if (!empty($card->CardFiles)) {
                    foreach ($card->CardFiles as $cardFile) {
                        $cardFile->file;
                    }
                }
            }
        }

        return $cards;
    }

    public function filtered(Request $request)
    {
        $page = $request->get('page');
        $size = $request->get('size');
        $type = $request->get('type');
        $sale_type = $request->get('sale_type');
        $category = $request->get('category');
        $subcategory = $request->get('subcategory');
        $stage_transaction = $request->get('stage_transaction');
        $user_id = $request->get('user_id');
        $sort = explode(',', $request->get('sort'));

        $query = Card::query();
        if ($type) {
            $query->where('type', 'like', $type)->get();
        }

        if ($sale_type) {
            $query->where('sale_type', 'like', $sale_type);
        }

        if ($category) {
            $query->where('category', 'like', $category);
        }

        if ($subcategory) {
            $query->where('subcategory', 'like', $subcategory);
        }

        if ($stage_transaction) {
            $query->where('stage_transaction', 'like', $stage_transaction);
        }

        if ($user_id) {
            $query->where('user_id', 'like', $user_id);
        }

        if (!$page) {
            $page = 1;
        }

        if (!$size) {
            $size = 10;
        }
        $flag = false;
        if (is_array($sort) and count($sort) > 1) {
            $object = new Card();
            if (in_array($sort[0], $object->getFields()) && in_array($sort[1], array('desc', 'asc'))) {
                $flag = true;
            }
        }

        if ($flag) {
            $cards = $query->offset($page * $size)->orderBy($sort[0], $sort[1])->paginate($size);
        } else {
            $cards = $query->offset($page * $size)->orderBy("created_at", 'desc')->paginate($size);
        }

        if (!empty($cards)) {
            foreach ($cards as $card) {
                $card->CardContact;
                if (!empty($card->CardContact)) {
                    $card->CardContact->CardsContactsPhones;
                }

                $card->CardFiles;
                $card->CardAgency;
                $card->CardOffice;
                $card->CardUser;

                if (!empty($card->CardFiles)) {
                    foreach ($card->CardFiles as $cardFile) {
                        $cardFile->file;
                    }
                }
            }
        }
        return $cards;
    }

    public function show($id)
    {
        $card = Card::find($id);
        if ($card) {

            $card->CardContact;
            if (!empty($card->CardContact)) {
                $card->CardContact->CardsContactsPhones;
            }

            $card->CardFiles;
            $card->CardAgency;
            $card->CardOffice;
            $card->CardUser;

            if (!empty($card->CardFiles)) {
                foreach ($card->CardFiles as $cardFile) {
                    $cardFile->file;
                }
            }
        }
        return response()->json($card, 200);
    }

    public function store(Request $request)
    {
        $cards_contacts_id = null;

        if (!$request->get('agency_id')) {
            return response()->json(array(), 402);
        }

        if (!empty($request->get('card_contact')) && is_array($request->get('card_contact'))) {

            $card_contact_data = $request->get('card_contact');

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

            }

        }

        if (!$cards_contacts_id) {
            return response()->json(array(), 402);
        }

        $card_data = $request->all();
        $card_data['cards_contacts_id'] = $cards_contacts_id;

        unset($card_data['card_contact']);
        unset($card_data['cards_file']);

        $card_data['user_id'] = (int)$card_data['user_id'];
        $card_data['agency_id'] = (int)$card_data['agency_id'];
        $card_data['office_id'] = (int)$card_data['office_id'];

        /*if (isset($card_data['year_built'])) {
            $card_data['year_built'] = (int)$card_data['year_built'];
        } else {
            $card_data['year_built'] = null;
        }*/

        /*if (isset($card_data['number_rooms'])) {
            $card_data['number_rooms'] = (int)$card_data['number_rooms'];
        } else {
            $card_data['number_rooms'] = null;
        }*/

        if (isset($card_data['number_contract'])) {
            $card_data['number_contract'] = (int)$card_data['number_contract'];
        } else {
            $card_data['number_contract'] = null;
        }

        if (isset($card_data['contract_expiration_date'])) {
            $card_data['contract_expiration_date'] = (int)$card_data['contract_expiration_date'];
        } else {
            $card_data['contract_expiration_date'] = null;
        }

        $card_data['is_archived'] = ((isset($card_data['is_archived']) && $card_data['is_archived'] == 'true') ? true : false);

        $card = Card::create($card_data);

        if ($card) {

            if (!empty($request->get('cards_file')) && is_array($request->get('cards_file'))) {

                $cards_file_data = $request->get('cards_file');

                foreach($cards_file_data as $key => $value) {

                    CardsFile::create([
                        'card_id' => $card->id,
                        'type' => (isset($value['type']) ? $value['type'] : ''),
                        'file_id' => (isset($value['file_id']) ? $value['file_id'] : '')
                    ]);

                }

            }

        }

        $card->CardContact;
        if (!empty($card->CardContact)) {
            $card->CardContact->CardsContactsPhones;
        }

        $card->CardFiles;
        if (!empty($card->CardFiles)) {
            foreach ($card->CardFiles as $cardFile) {
                $cardFile->file;
            }
        }
        $card->CardAgency;
        $card->CardOffice;
        $card->CardUser;

        return response()->json($card, 201);
    }

    public function update(Request $request, $id)
    {
        $card = Card::find($id);
        if ($card) {

            $card_data = $request->all();

            unset($card_data['card_contact']);
            unset($card_data['cards_file']);

            if (isset($card_data['user_id'])) {
                $card_data['user_id'] = (int)$card_data['user_id'];
            }

            if (isset($card_data['agency_id'])) {
                $card_data['agency_id'] = (int)$card_data['agency_id'];
            }

            if (isset($card_data['office_id'])) {
                $card_data['office_id'] = (int)$card_data['office_id'];
            }

            /*if (isset($card_data['year_built'])) {
                $card_data['year_built'] = (int)$card_data['year_built'];
            }*/

            /*if (isset($card_data['number_rooms'])) {
                $card_data['number_rooms'] = (int)$card_data['number_rooms'];
            }*/

            if (isset($card_data['number_contract'])) {
                $card_data['number_contract'] = (int)$card_data['number_contract'];
            }

            if (isset($card_data['contract_expiration_date'])) {
                $card_data['contract_expiration_date'] = (int)$card_data['contract_expiration_date'];
            }

            if (isset($card_data['contract_expiration_date'])) {
                $card_data['contract_expiration_date'] = (int)$card_data['contract_expiration_date'];
            }

            if (isset($card_data['is_archived'])) {
                $card_data['is_archived'] = (($card_data['is_archived'] == 'true') ? true : false);
            }

            $card->update($card_data);

            if ($card) {
                if (!empty($request->get('cards_file')) && is_array($request->get('cards_file'))) {

                    $cards_file_data = $request->get('cards_file');

                    foreach($cards_file_data as $key => $value) {

                        $cards_file = CardsFile::find($key);
                        if ($cards_file) {

                            $cards_file->type = (isset($value['type']) ? $value['type'] : '');
                            $cards_file->file_id = (isset($value['file_id']) ? $value['file_id'] : '');
                            $cards_file->save();

                        } else {

                            CardsFile::create([
                                'card_id' => $card->id,
                                'type' => (isset($value['type']) ? $value['type'] : ''),
                                'file_id' => (isset($value['file_id']) ? $value['file_id'] : '')
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

        $card->CardContact;
        if (!empty($card->CardContact)) {
            $card->CardContact->CardsContactsPhones;
        }

        $card->CardFiles;
        if (!empty($card->CardFiles)) {
            foreach ($card->CardFiles as $cardFile) {
                $cardFile->file;
            }
        }
        $card->CardAgency;
        $card->CardOffice;
        $card->CardUser;

        return response()->json($card, 200);
    }

    public function delete(Request $request, $id) {
        $card = Card::find($id);

        if ($card) {
            $card->delete();
        }

        return response()->json(null, 204);
    }

    public  function cardsDeleteFile(Request $request, $card_id, $file_id) {
        $card = Card::find($card_id);
        if ($card) {
           $card_file = CardsFile::find($file_id);
           if ($card_file) {
               $card_file->delete();
           }
        }
        return response()->json(null, 204);
    }

    public function findContactByPhone(Request $request) {
        $phone = $request->get('phone');
        $agency_id = $request->get('agency_id');

        if (!$phone) {
            return response()->json(array(), 402);
        }

        $contact_phone = CardContactsPhones::where('phone', 'like', $phone)->where('agency_id', '=', $agency_id)->first();

        if (!$contact_phone) {
            return response()->json(array(), 402);
        }

        $contact = CardContacts::find($contact_phone->cards_contacts_id);

        if (!$contact) {
            return response()->json(array(), 402);
        }

        $contact->CardsContactsPhones;

        return response()->json($contact, 200);
    }

    public function deleteCardsContactById(Request $request, $id) {
        $card = CardContacts::find($id);
        if ($card) {
            $card->delete();
        }
        return response()->json(null, 204);
    }

    public function cardsContactIsBlackList(Request $request, $id) {
        $is_black_list = $request->get('is_black_list');
        if (!isset($is_black_list)) {
            return response()->json(array(), 402);
        }
        $card = CardContacts::find($id);
        if ($card) {
            $card->is_black_list = (int) $is_black_list;
            $card->save();
        }
        return response()->json(null, 204);
    }

    public function nearCards($id) {
        $card = Card::where('is_archived', '=', 0)
            ->where('id', '=', $id)->first();
        if (!$card) {
            return response()->json([], 402);
        }

        $second_near_array = array(
            'area', 'bathroom', 'ceiling_height', 'condition', 'electricity', 'entrance_door', 'furniture', 'garbage_chute',
            'gas', 'heating', 'how_plot_fenced', 'internet', 'kitchen_area', 'land_area', 'layout', 'living_area', 'number_rooms', 'price', 'roof', 'security',
            'sewage', 'total_area', 'type_building', 'view_from_windows', 'water_pipes', 'window'
        );

        $cards = Card::where('category', 'like', $card->category)
            ->where('is_archived', '=', 0)
            ->where('city', 'like', $card->city)
            ->where('is_archived', 'like', $card->city)
            ->where('subcategory', 'like', $card->subcategory)
            ->where('type', 'like', $card->type)->get();

        if (empty($cards)) {
            return response()->json([], 204);
        }

        if (!empty($cards)) {
            foreach ($cards as $card) {
                $card->CardContact;
                if (!empty($card->CardContact)) {
                    $card->CardContact->CardsContactsPhones;
                }

                $card->CardFiles;
                $card->CardAgency;
                $card->CardOffice;
                $card->CardUser;

                if (!empty($card->CardFiles)) {
                    foreach ($card->CardFiles as $cardFile) {
                        $cardFile->file;
                    }
                }
            }
        }
        return response()->json($cards, 200);
    }

}
