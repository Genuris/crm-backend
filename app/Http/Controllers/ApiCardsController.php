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
        'POST' => ['add' => ['api/cards', 'api/cards_contact_phone', 'api/cards_filtered', 'api/near_cards']],
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
        $price_from = $request->get('price_from');
        $price_to = $request->get('price_to');
        $sort = explode(',', $request->get('sort'));

        $query = Card::query();
        if ($type) {
            $query->where('type', 'like', $type);
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
        
        if ($price_from) {
            $query->where('price', '>=', (float)$price_from);
        }

        if ($price_to) {
            $query->where('price', '<=', (float)$price_to);
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

    public function nearCards(Request $request, $id) {
        $user = $request->user();

        if (!$user) {
            return response()->json(array('error' => array('status' => 401, 'message' => 'Unauthorized. The user needs to be authenticated.')), 401);
        }

        $query = Card::query();
        $query->where('is_archived', '=', 0)->where('id', '=', $id);
        if ($user->agency_id) {
            $query->where('agency_id', '=', $user->agency_id);
        }

        $card = $query->first();
        if (!$card) {
            return response()->json([], 402);
        }

        //kitchen_area
        //land_area
        //living_area
        //price
        //total_area
        $second_near_array = array(
            'area', 'bathroom', 'ceiling_height', 'condition', 'electricity', 'entrance_door', 'furniture', 'garbage_chute',
            'gas', 'heating', 'how_plot_fenced', 'internet', 'kitchen_area', 'land_area', 'layout', 'living_area', 'number_rooms', 'price', 'roof', 'security',
            'sewage', 'total_area', 'type_building', 'view_from_windows', 'water_pipes', 'window'
        );

        $query = Card::query();

        $query->where('is_archived', '=', 0);
        $query->where('id', '!=', $id);
        $query->where('agency_id', '=', $card->agency_id);

        if (!is_null($card->category)) {
            $query->where('category', 'like', $card->category);
        }

        if (!is_null($card->city)) {
            $query->where('city', 'like', $card->city);
        }

        $sale_type = (int)$request->get('sale_type');

        if (isset($sale_type) && $sale_type === 1) {
            if (!is_null($card->sale_type)) {
                $query->where('sale_type', 'not like', $card->sale_type);
            }
        } else {
            if (!is_null($card->sale_type)) {
                $query->where('sale_type', '!=', $card->sale_type);
            }
        }

        if (!is_null($card->subcategory)) {
            $query->where('subcategory', 'like', $card->subcategory);
        }

        if (!is_null($card->type)) {
            $query->where('type', 'like', $card->type);
        }

        $cards = $query->get();

        if (empty($cards)) {
            return response()->json([], 204);
        }

        if (!empty($cards)) {
            foreach ($cards as $card_near) {
                $percent = 0;

                if (!is_null($card->area) && !is_null($card_near->area) && $card_near->area == $card->area) {
                    $percent+=1;
                }

                if (!is_null($card->bathroom) && !is_null($card_near->bathroom) && $card_near->bathroom == $card->bathroom) {
                    $percent+=1;
                }

                if (!is_null($card->ceiling_height) && !is_null($card_near->ceiling_height) && $card_near->ceiling_height == $card->ceiling_height) {
                    $percent+=1;
                }

                if (!is_null($card->condition) && !is_null($card_near->condition) && $card_near->condition == $card->condition) {
                    $percent+=1;
                }

                if (!is_null($card->electricity) && !is_null($card_near->electricity) && $card_near->electricity == $card->electricity) {
                    $percent+=1;
                }

                if (!is_null($card->entrance_door) && !is_null($card_near->entrance_door) && $card_near->entrance_door == $card->entrance_door) {
                    $percent+=1;
                }

                if (!is_null($card->furniture) && !is_null($card_near->furniture) && $card_near->furniture == $card->furniture) {
                    $percent+=1;
                }

                if (!is_null($card->garbage_chute) && !is_null($card_near->garbage_chute) && $card_near->garbage_chute == $card->garbage_chute) {
                    $percent+=1;
                }

                if (!is_null($card->gas) && !is_null($card_near->gas) && $card_near->gas == $card->gas) {
                    $percent+=1;
                }

                if (!is_null($card->heating) && !is_null($card_near->heating) && $card_near->heating == $card->heating) {
                    $percent+=1;
                }

                if (!is_null($card->how_plot_fenced) && !is_null($card_near->how_plot_fenced) && $card_near->how_plot_fenced == $card->how_plot_fenced) {
                    $percent+=1;
                }

                if (!is_null($card->internet) && !is_null($card_near->internet) && $card_near->internet == $card->internet) {
                    $percent+=1;
                }

                if (!is_null($card->layout) && !is_null($card_near->layout) && $card_near->layout == $card->layout) {
                    $percent+=1;
                }

                if (!is_null($card->number_rooms) && !is_null($card_near->number_rooms) && $card_near->number_rooms == $card->number_rooms) {
                    $percent+=1;
                }

                if (!is_null($card->roof) && !is_null($card_near->roof) && $card_near->roof == $card->roof) {
                    $percent+=1;
                }

                if (!is_null($card->security) && !is_null($card_near->security) && $card_near->security == $card->security) {
                    $percent+=1;
                }

                if (!is_null($card->sewage) && !is_null($card_near->sewage) && $card_near->sewage == $card->sewage) {
                    $percent+=1;
                }

                if (!is_null($card->type_building) && !is_null($card_near->type_building) && $card_near->type_building == $card->type_building) {
                    $percent+=1;
                }

                if (!is_null($card->view_from_windows) && !is_null($card_near->view_from_windows) && $card_near->view_from_windows == $card->view_from_windows) {
                    $percent+=1;
                }

                if (!is_null($card->water_pipes) && !is_null($card_near->water_pipes) && $card_near->water_pipes == $card->water_pipes) {
                    $percent+=1;
                }

                if (!is_null($card->window) && !is_null($card_near->window) && $card_near->window == $card->window) {
                    $percent+=1;
                }

                $card_near->percent = $percent;

                $card_near->CardContact;
                if (!empty($card_near->CardContact)) {
                    $card_near->CardContact->CardsContactsPhones;
                }

                $card_near->CardFiles;
                $card_near->CardAgency;
                $card_near->CardOffice;
                $card_near->CardUser;

                if (!empty($card_near->CardFiles)) {
                    foreach ($card_near->CardFiles as $cardFile) {
                        $cardFile->file;
                    }
                }
            }
        }
        return response()->json($cards, 200);
    }

}
