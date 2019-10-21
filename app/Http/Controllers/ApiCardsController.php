<?php

namespace App\Http\Controllers;

use App\Models\CardRequestPosts;
use App\Models\CardRequestStatus;
use App\Models\Role;
use App\Modules\DataChangeLog\DataChangeLog;
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
        'POST' => ['add' => ['api/cards', 'api/cards_contact_phone', 'api/cards_filtered', 'api/near_cards', 'api/cards_request_status', 'api/cards_request_get_statuses', 'api/cards_request_post', 'api/cards_request_get_posts']],
        'DELETE' => ['delete' => ['api/cards', 'api/cards_delete', 'api/cards_contact_delete']],
    );
    public $current_user_id = null;

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

            $this->current_user_id = $user->id;

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
                if ($card->CardUser) {
                    if ($card->CardUser->UserDetails) {
                        $card->CardUser->UserDetails->profileImage;
                    }
                    $card->CardUser->UserPhones;
                    $card->CardUser->UserSocials;
                }

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
        $contacts_id = $request->get('contacts_id');
        $is_archived = $request->get('is_archived');
        $sale_type = $request->get('sale_type');
        $category = $request->get('category');
        $subcategory = $request->get('subcategory');
        $floor_location = $request->get('floor_location');
        $stage_transaction = $request->get('stage_transaction');
        $user_id = $request->get('user_id');
        $price_from = $request->get('price_from');
        $price_to = $request->get('price_to');
        $city = $request->get('city');
        $area = $request->get('area');
        $street = $request->get('street');
        $sort = explode(',', $request->get('sort'));

        $total_area_from = $request->get('total_area_from');
        $total_area_to = $request->get('total_area_to');

        $total_area_end_from = $request->get('total_area_end_from');
        $total_area_end_to = $request->get('total_area_end_to');

        $floors_house_from = $request->get('floors_house_from');
        $floors_house_to = $request->get('floors_house_to');

        $floors_house_end_from = $request->get('floors_house_end_from');
        $floors_house_end_to = $request->get('floors_house_end_to');

        $number_of_floors_from = $request->get('number_of_floors_from');
        $number_of_floors_to = $request->get('number_of_floors_to');

        $number_of_floors_end_from = $request->get('number_of_floors_end_from');
        $number_of_floors_end_to = $request->get('number_of_floors_end_to');

        $query = Card::query();
        if ($type) {
            $query->where('type', 'like', $type);
        }

        if ($is_archived) {
            $query->where('is_archived', '=', (int)$is_archived);
        }

        if ($contacts_id) {
            $query->where('cards_contacts_id', '=', $contacts_id);
        }

        if ($sale_type) {
            $query->where('sale_type', 'like', $sale_type);
        }

        if ($category) {
            $query->where('category', 'like', $category);
        }

//        if ($subcategory) {
//            $query->where('subcategory', 'REGEXP', '\b'.$subcategory.'\b');
//        }

        if (!is_null($subcategory)) {
            $subcategories = explode(",", $subcategory);
            if (is_array($subcategories) && !empty($subcategories) && count($subcategories) > 1) {
                $subcategory_search_array = [];
                $flagIsNull = false;
                foreach($subcategories as $i => $subcat) {
                    if ($subcat === 'null') {
                        $flagIsNull = true;
                    } else {
                        $subcategory_search_array[] = $subcat;
                    }
                }
                if (count($subcategory_search_array) > 1) {
                    $subcategory_search_array = implode("|", $subcategory_search_array);
                    if ($flagIsNull) {
                        $query->where(function ($q) use ($subcategory_search_array) {
                            $q->where('subcategory', 'REGEXP', '\b'.$subcategory_search_array.'\b');
                            $q->orWhereNull('subcategory');
                        });
                    } else {
                        $query->where('subcategory', 'REGEXP', '\b'.$subcategory_search_array.'\b');
                    }
                } else if(count($subcategory_search_array) > 0){
                    if ($flagIsNull) {
                        $query->where(function ($q) use ($subcategory_search_array) {
                            $q->where('subcategory', 'REGEXP', '\b'.$subcategory_search_array[0].'\b');
                            $q->orWhereNull('subcategory');
                        });
                    } else {
                        $query->where('subcategory', 'REGEXP', '\b'.$subcategory_search_array.'\b');
                    }
                } else {
                    if ($flagIsNull) {
                        $query->orWhereNull('subcategory');
                    }
                }
            } else {
                if ($subcategory === 'null') {
                    $query->where('subcategory', '=', NULL);
                } else {
                    $query->where('subcategory', 'REGEXP', '\b'.$subcategory.'\b');
                }
            }

            /*$query_ = str_replace(array('?'), array('\'%s\''), $query->toSql());
            $query_ = vsprintf($query_, $query->getBindings());
            dd($query_);*/
        }

        if (!is_null($floor_location)) {
            $floor_locations = explode(",", $floor_location);
            if (is_array($floor_locations) && !empty($floor_locations) && count($floor_locations) > 1) {
                $floor_locations_array = [];
                $flagIsNull = false;
                foreach($floor_locations as $i => $subcat) {
                    if ($subcat === 'null') {
                        $flagIsNull = true;
                    } else {
                        $floor_locations_array[] = $subcat;
                    }
                }
                if (count($floor_locations_array) > 1) {
                    $floor_locations_array = implode("|", $floor_locations_array);
                    if ($flagIsNull) {
                        $query->where(function ($q) use ($floor_locations_array) {
                            $q->where('floor_location', 'REGEXP', '\b'.$floor_locations_array.'\b');
                            $q->orWhereNull('floor_location');
                        });
                    } else {
                        $query->where('floor_location', 'REGEXP', '\b'.$floor_locations_array.'\b');
                    }
                } else if(count($floor_locations_array) > 0){
                    if ($flagIsNull) {
                        $query->where(function ($q) use ($floor_locations_array) {
                            $q->where('floor_location', 'REGEXP', '\b'.$floor_locations_array[0].'\b');
                            $q->orWhereNull('floor_location');
                        });
                    } else {
                        $query->where('floor_location', 'REGEXP', '\b'.$floor_locations_array.'\b');
                    }
                } else {
                    if ($flagIsNull) {
                        $query->orWhereNull('floor_location');
                    }
                }
            } else {
                if ($floor_location === 'null') {
                    $query->where('floor_location', '=', NULL);
                } else {
                    $query->where('floor_location', 'REGEXP', '\b'.$floor_location.'\b');
                }
            }

            /*$query_ = str_replace(array('?'), array('\'%s\''), $query->toSql());
            $query_ = vsprintf($query_, $query->getBindings());
            dd($query_);*/
        }

        if (!is_null($street)) {
            $streets = explode(",", $street);
            if (is_array($streets) && !empty($streets) && count($streets) > 1) {
                $street_search_array = [];
                $flagIsNull = false;
                foreach($streets as $i => $str) {
                    if ($str === 'null') {
                        $flagIsNull = true;
                    } else {
                        $street_search_array[] = $str;
                    }
                }
                if (count($street_search_array) > 1) {
                    $street_search_array = implode("|", $street_search_array);
                    if ($flagIsNull) {
                        $query->where(function ($q) use ($street_search_array) {
                            $q->where('street', 'REGEXP', '\b'.$street_search_array.'\b');
                            $q->orWhereNull('street');
                        });
                    } else {
                        $query->where('street', 'REGEXP', '\b'.$street_search_array.'\b');
                    }
                } else if(count($street_search_array) > 0){
                    if ($flagIsNull) {
                        $query->where(function ($q) use ($street_search_array) {
                            $q->where('street', 'REGEXP', '\b'.$street_search_array[0].'\b');
                            $q->orWhereNull('street');
                        });
                    } else {
                        $query->where('street', 'REGEXP', '\b'.$street_search_array.'\b');
                    }
                } else {
                    if ($flagIsNull) {
                        $query->orWhereNull('street');
                    }
                }
            } else {
                if ($street === 'null') {
                    $query->where('street', '=', NULL);
                } else {
                    $query->where('street', 'REGEXP', '\b'.$street.'\b');
                }
            }

            /*$query_ = str_replace(array('?'), array('\'%s\''), $query->toSql());
            $query_ = vsprintf($query_, $query->getBindings());
            dd($query_);*/
        }

        if (!is_null($area)) {
            $areas = explode(",", $area);
            if (is_array($areas) && !empty($areas) && count($areas) > 1) {
                $areas_search_array = [];
                $flagIsNull = false;
                foreach($areas as $i => $str) {
                    if ($str === 'null') {
                        $flagIsNull = true;
                    } else {
                        $areas_search_array[] = $str;
                    }
                }
                if (count($areas_search_array) > 1) {
                    $areas_search_array = implode("|", $areas_search_array);
                    if ($flagIsNull) {
                        $query->where(function ($q) use ($areas_search_array) {
                            $q->where('area', 'REGEXP', '\b'.$areas_search_array.'\b');
                            $q->orWhereNull('area');
                        });
                    } else {
                        $query->where('area', 'REGEXP', '\b'.$areas_search_array.'\b');
                    }
                } else if(count($areas_search_array) > 0){
                    if ($flagIsNull) {
                        $query->where(function ($q) use ($areas_search_array) {
                            $q->where('area', 'REGEXP', '\b'.$areas_search_array[0].'\b');
                            $q->orWhereNull('area');
                        });
                    } else {
                        $query->where('area', 'REGEXP', '\b'.$areas_search_array.'\b');
                    }
                } else {
                    if ($flagIsNull) {
                        $query->orWhereNull('area');
                    }
                }
            } else {
                if ($area === 'null') {
                    $query->where('area', '=', NULL);
                } else {
                    $query->where('area', 'REGEXP', '\b'.$area.'\b');
                }
            }

            /*$query_ = str_replace(array('?'), array('\'%s\''), $query->toSql());
            $query_ = vsprintf($query_, $query->getBindings());
            dd($query_);*/
        }

        if (!is_null($city)) {
            $cities = explode(",", $city);
            if (is_array($cities) && !empty($cities) && count($cities) > 1) {
                $cities_search_array = [];
                $flagIsNull = false;
                foreach($cities as $i => $str) {
                    if ($str === 'null') {
                        $flagIsNull = true;
                    } else {
                        $cities_search_array[] = $str;
                    }
                }
                if (count($cities_search_array) > 1) {
                    $cities_search_array = implode("|", $cities_search_array);
                    if ($flagIsNull) {
                        $query->where(function ($q) use ($cities_search_array) {
                            $q->where('city', 'REGEXP', '\b'.$cities_search_array.'\b');
                            $q->orWhereNull('city');
                        });
                    } else {
                        $query->where('city', 'REGEXP', '\b'.$cities_search_array.'\b');
                    }
                } else if(count($cities_search_array) > 0){
                    if ($flagIsNull) {
                        $query->where(function ($q) use ($cities_search_array) {
                            $q->where('city', 'REGEXP', '\b'.$cities_search_array[0].'\b');
                            $q->orWhereNull('city');
                        });
                    } else {
                        $query->where('city', 'REGEXP', '\b'.$cities_search_array.'\b');
                    }
                } else {
                    if ($flagIsNull) {
                        $query->orWhereNull('city');
                    }
                }
            } else {
                if ($city === 'null') {
                    $query->where('city', '=', NULL);
                } else {
                    $query->where('city', 'REGEXP', '\b'.$city.'\b');
                }
            }

            /*$query_ = str_replace(array('?'), array('\'%s\''), $query->toSql());
            $query_ = vsprintf($query_, $query->getBindings());
            dd($query_);*/
        }

        /*if (!is_null($street)) {
            $streets = explode(",", $street);
            if (is_array($streets) && !empty($streets) && count($streets) > 1) {
                $query->whereIn('street', $streets);
            } else {
                $query->where('street', 'REGEXP', '\b'.$street.'\b');
            }
        }

        if (!is_null($area)) {
            $areas = explode(",", $area);
            if (is_array($areas) && !empty($areas) && count($areas) > 1) {
                $query->whereIn('area', $areas);
            } else {
                $query->where('area', 'REGEXP', '\b'.$area.'\b');
            }
        }

        if (!is_null($city)) {
            $cities = explode(",", $city);
            if (is_array($cities) && !empty($cities) && count($cities) > 1) {
                $query->whereIn('city', $cities);
            } else {
                $query->where('city', 'REGEXP', '\b'.$city.'\b');
            }
        }*/

        if (!is_null($stage_transaction)) {
            $stage_transactions = explode(",", $stage_transaction);
            if (is_array($stage_transactions) && !empty($stage_transactions) && count($stage_transactions) > 1) {
                $query->whereIn('stage_transaction', $stage_transactions);
            } else {
                $query->where('stage_transaction', 'REGEXP', '\\b'.$stage_transaction.'\\b');
            }
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

        if ($total_area_from) {
            $query->where('total_area', '>=', (float)$total_area_from);
        }

        if ($total_area_to) {
            $query->where('total_area', '<=', (float)$total_area_to);
        }

        if ($total_area_end_from) {
            $query->where('total_area_end', '>=', (float)$total_area_end_from);
        }

        if ($total_area_end_to) {
            $query->where('total_area_end', '<=', (float)$total_area_end_to);
        }

        if ($floors_house_from) {
            $query->where('floors_house', '>=', (int)$floors_house_from);
        }

        if ($floors_house_to) {
            $query->where('floors_house', '<=', (int)$floors_house_to);
        }

        if ($floors_house_end_from) {
            $query->where('floors_house_end', '>=', (int)$floors_house_end_from);
        }

        if ($floors_house_end_to) {
            $query->where('floors_house_end', '<=', (int)$floors_house_end_to);
        }

        if ($number_of_floors_from) {
            $query->where('number_of_floors', '>=', (int)$number_of_floors_from);
        }

        if ($number_of_floors_to) {
            $query->where('number_of_floors', '<=', (int)$number_of_floors_to);
        }

        if ($number_of_floors_end_from) {
            $query->where('number_of_floors_end', '>=', (int)$number_of_floors_end_from);
        }

        if ($number_of_floors_end_to) {
            $query->where('number_of_floors_end', '<=', (int)$number_of_floors_end_to);
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

//        $query_ = str_replace(array('?'), array('\'%s\''), $query->toSql());
//        $query_ = vsprintf($query_, $query->getBindings());
//        dd($query_);

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
                if ($card->CardUser) {
                    if ($card->CardUser->UserDetails) {
                        $card->CardUser->UserDetails->profileImage;
                    }
                    $card->CardUser->UserPhones;
                    $card->CardUser->UserSocials;
                }

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
            if ($card->CardUser) {
                if ($card->CardUser->UserDetails) {
                    $card->CardUser->UserDetails->profileImage;
                }
                $card->CardUser->UserPhones;
                $card->CardUser->UserSocials;
            }
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

        if (isset($card_data['price'])) {
            $card_data['price'] = (float)$card_data['price'];
        } else {
            $card_data['price'] = null;
        }

        if (isset($card_data['contract_expiration_date'])) {
            $card_data['contract_expiration_date'] = (int)$card_data['contract_expiration_date'];
        } else {
            $card_data['contract_expiration_date'] = null;
        }

        $card_data['is_archived'] = ((isset($card_data['is_archived']) && $card_data['is_archived'] == 'true') ? true : false);
        $card_data['creator_id'] = $this->current_user_id;

        if (isset($card_data['currency'])  && !is_null($card_data['currency']) && isset($card_data['price']) && !is_null($card_data['price'])) {
            $card_data['data_change_prices'][] = array(
                'time' => time(),
                'currency' => $card_data['currency'],
                'price' => $card_data['price'],
                'user_id' => $this->current_user_id,
            );
            $card_data['data_change_prices'] = json_encode($card_data['data_change_prices']);
        }

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
        if ($card->CardUser) {
            if ($card->CardUser->UserDetails) {
                $card->CardUser->UserDetails->profileImage;
            }
            $card->CardUser->UserPhones;
            $card->CardUser->UserSocials;
        }

        return response()->json($card, 201);
    }

    public function update(Request $request, $id)
    {
        $card = Card::find($id);
        if ($card) {
            $card_data = $request->all();

            $data_change_logs = ((isset($card_data['data_change_logs']) && !empty($card_data['data_change_logs'])) ? $card_data['data_change_logs'] : []);

            unset($card_data['card_contact']);
            unset($card_data['cards_file']);
            unset($card_data['creator_id']);
            unset($card_data['data_change_logs']);

            if (isset($card_data['user_id'])) {
                $card_data['user_id'] = (int)$card_data['user_id'];
            }

            if (isset($card_data['agency_id'])) {
                $card_data['agency_id'] = (int)$card_data['agency_id'];
            }

            if (isset($card_data['office_id'])) {
                $card_data['office_id'] = (int)$card_data['office_id'];
            }

            if (isset($card_data['price'])) {
                $card_data['price'] = (float)$card_data['price'];
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

            if (
                isset($card_data['price']) && $card->price != (float)$card_data['price'] ||
                isset($card_data['currency']) && $card->currency != (float)$card_data['currency']
            ) {
                $card_data['data_change_prices'] = json_decode($card->data_change_prices, true);
                if (is_array($card_data['data_change_prices']) && !empty($card_data['data_change_prices'])) {
                    $card_data['data_change_prices'][] = array(
                        'time' => time(),
                        'currency' => $card_data['currency'],
                        'price' => $card_data['price'],
                        'user_id' => $this->current_user_id,
                    );
                } else {
                    $card_data['data_change_prices'][] = array(
                        'time' => time(),
                        'currency' => $card->currency,
                        'price' => $card->price,
                        'user_id' => $this->current_user_id,
                    );
                    $card_data['data_change_prices'][] = array(
                        'time' => time(),
                        'currency' => $card_data['currency'],
                        'price' => $card_data['price'],
                        'user_id' => $this->current_user_id,
                    );
                }
                $card_data['data_change_prices'] = json_encode($card_data['data_change_prices']);
            }

            $card->update($card_data);

            if ($card) {
                if (isset($data_change_logs) && !empty($data_change_logs) && is_array($data_change_logs)) {
                    DataChangeLog::setLogs($card->object_name, $id, $this->current_user_id, $data_change_logs);
                }
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
        if ($card->CardUser) {
            if ($card->CardUser->UserDetails) {
                $card->CardUser->UserDetails->profileImage;
            }
            $card->CardUser->UserPhones;
            $card->CardUser->UserSocials;
        }

        return response()->json($card, 200);
    }

    public function delete(Request $request, $id) {
        $card = Card::find($id);
        if ($card) {
            $card_files = CardsFile::where('card_id', '=', $id)->get();
            if (!empty($card_files)) {
                foreach($card_files as $card_file) {
                    if (ApiFilesController::deleteFile($card_file->file_id)) {
                        $card_file->delete();
                    }
                }
            }
            $card->delete();
        }
        return response()->json(null, 204);
    }

    public  function cardsDeleteFile(Request $request, $card_id, $file_id) {
        $card = Card::find($card_id);
        if ($card) {
           $card_file = CardsFile::find($file_id);
           if ($card_file) {
               if (ApiFilesController::deleteFile($card_file->file_id)) {
                   $card_file->delete();
               }
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
        //total_area
        //price
        /*$second_near_array = array(
            'area', 'bathroom', 'ceiling_height', 'condition', 'electricity', 'entrance_door', 'furniture', 'garbage_chute',
            'gas', 'heating', 'how_plot_fenced', 'internet', 'kitchen_area', 'land_area', 'layout', 'living_area', 'number_rooms', 'price', 'roof', 'security',
            'sewage', 'total_area', 'type_building', 'view_from_windows', 'water_pipes', 'window'
        );*/

        $query = Card::query();

        $query->where('is_archived', '=', 0);
        $query->where('id', '!=', $id);
        $query->where('agency_id', '=', $card->agency_id);

        if (!is_null($card->category)) {
            $query->where('category', 'like', $card->category);
        }

        /*if (!is_null($card->city)) {
            $query->where('city', 'like', $card->city);
        }*/

        $sale_type = (int)$request->get('sale_type');

        if (isset($sale_type) && $sale_type === 1) {
            if (!is_null($card->sale_type)) {
                $query->where('sale_type', 'like', $card->sale_type);
            }
        } else {
            if (!is_null($card->sale_type)) {
                $query->where('sale_type', 'not like', $card->sale_type);
            }
        }

        if (!is_null($card->area)) {
            $areas = explode(",", $card->area);
            if (is_array($areas) && !empty($areas) && count($areas) > 1) {
                $query->where(function ($q) use ($areas){
                    $q->whereIn('area', $areas);
                    $q->orWhereNull('area');
                });
            } else {
                $query->where(function ($q) use ($card){
                    $q->where('area', 'REGEXP', '\b'.$card->area.'\b');
                    $q->orWhereNull('area');
                });
            }

        }

        if (!is_null($card->subcategory)) {
            $subcategories = explode(",", $card->subcategory);
            if (is_array($subcategories) && !empty($subcategories) && count($subcategories) > 1) {
                $query->whereIn('subcategory', $subcategories);
            } else {
                $query->where('subcategory', 'REGEXP', '\b'.$card->subcategory.'\b');
            }
        }

        if (!is_null($card->city)) {
            $cities = explode(",", $card->city);
            if (is_array($cities) && !empty($cities) && count($cities) > 1) {
                $query->whereIn('city', $cities);
            } else {
                $query->where('city', 'REGEXP', '\b'.$card->city.'\b');
            }
        }

        if (!is_null($card->street)) {
            $streets = explode(",", $card->street);
            if (is_array($streets) && !empty($streets) && count($streets) > 1) {
                $query->where(function ($q) use ($streets){
                    $q->whereIn('street', $streets);
                    $q->orWhereNull('street');
                });
            } else {
                $query->where(function ($q) use ($card){
                    $q->where('street', 'REGEXP', '\b'.$card->street.'\b');
                    $q->orWhereNull('street');
                });
            }
        }

        /*if (!is_null($card->street)) {
            $streets = explode(",", $card->street);
            if (is_array($streets) && !empty($streets) && count($streets) > 1) {
                $query->whereIn('street', $streets);
            } else {
                $query->where('street', 'REGEXP', '\b'.$card->street.'\b');
            }
        }*/

        if (!is_null($card->type)) {
            $query->where('type', 'like', $card->type);
        }

        /*$query_ = str_replace(array('?'), array('\'%s\''), $query->toSql());
        $query_ = vsprintf($query_, $query->getBindings());
        dd($query_);*/
        $cards = $query->orderBy("created_at", 'desc')->get();

        if (empty($cards)) {
            return response()->json([], 204);
        }

        if (!empty($cards)) {
            foreach ($cards as $card_near) {
                $percent = 0;

                /*if (!is_null($card->area) && !is_null($card_near->area) && $card_near->area == $card->area) {
                    $percent+=1;
                }*/

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

                if (!is_null($card->elevator) && !is_null($card_near->elevator) && $card_near->elevator == $card->elevator) {
                    $percent+=1;
                }

                if (isset($sale_type) && $sale_type === 1) {
                    if (!is_null($card->sale_type)) {
                        //kitchen_area
                        if ($card->sale_type === 'object') {
                            if (!is_null($card_near->price)) {
                                if ((float)$card->price <= (float)$card_near->price) {
                                    $percent+=1;
                                }
                            }
                            if (!is_null($card_near->kitchen_area)) {
                                if (((float)$card->kitchen_area/(float)$card_near->kitchen_area) >= 0.8 && ((float)$card->kitchen_area/(float)$card_near->kitchen_area) <= 1.25) {
                                    $percent+=1;
                                }
                            }
                            if (!is_null($card_near->total_area)) {
                                if (((float)$card->total_area/(float)$card_near->total_area) >= 0.8 && ((float)$card->total_area/(float)$card_near->total_area) <= 1.25) {
                                    $percent+=1;
                                }
                            }

                            if (!is_null($card_near->land_area)) {
                                if (((float)$card->land_area/(float)$card_near->land_area) >= 0.8 && ((float)$card->land_area/(float)$card_near->land_area) <= 1.25) {
                                    $percent+=1;
                                }
                            }

                            if (!is_null($card_near->living_area)) {
                                if (((float)$card->living_area/(float)$card_near->living_area) >= 0.8 && ((float)$card->living_area/(float)$card_near->living_area) <= 1.25) {
                                    $percent+=1;
                                }
                            }
                        }
                    }
                } else {
                    if (!is_null($card->sale_type)) {
                        //total_area
                        if (!is_null($card_near->total_area) && !is_null($card->total_area)) {
                            if ($card->sale_type === 'object') {
                                $total_area = explode(",", $card_near->total_area);
                                if (isset($total_area[0]) && isset($total_area[1]) && (int)$card->total_area >= (int)$total_area[0] && (int)$card->total_area <= (int)$total_area[1]) {
                                    $percent+=1;
                                }
                            } else {
                                $total_area = explode(",", $card->total_area);
                                if (isset($total_area[0]) && isset($total_area[1]) && (int)$total_area[0] <= $card_near->total_area && (int)$total_area[1] >= (int)$card_near->total_area) {
                                    $percent+=1;
                                }
                            }
                        }
                        //land_area
                        if (!is_null($card_near->land_area) && !is_null($card->land_area)) {
                            if ($card->sale_type === 'object') {
                                $land_area = explode(",", $card_near->land_area);
                                if (isset($land_area[0]) && isset($land_area[1]) && (int)$card->land_area >= (int)$land_area[0] && (int)$card->land_area <= (int)$land_area[1]) {
                                    $percent+=1;
                                }
                            } else {
                                $land_area = explode(",", $card->land_area);
                                if (isset($land_area[0]) && isset($land_area[1]) && (int)$land_area[0] <= $card_near->land_area && (int)$land_area[1] >= (int)$card_near->land_area) {
                                    $percent+=1;
                                }
                            }
                        }
                        //living_area
                        if (!is_null($card_near->living_area) && !is_null($card->living_area)) {
                            if ($card->sale_type === 'object') {
                                $living_area = explode(",", $card_near->living_area);
                                if (isset($living_area[0]) && isset($living_area[1]) && (int)$card->living_area >= (int)$living_area[0] && (int)$card->living_area <= (int)$living_area[1]) {
                                    $percent+=1;
                                }
                            } else {
                                $living_area = explode(",", $card->living_area);
                                if (isset($living_area[0]) && isset($living_area[1]) && (int)$living_area[0] <= $card_near->living_area && (int)$living_area[1] >= (int)$card_near->living_area) {
                                    $percent+=1;
                                }
                            }
                        }

                        //kitchen_area
                        if (!is_null($card_near->kitchen_area) && !is_null($card->kitchen_area)) {
                            if ($card->sale_type === 'object') {
                                $kitchen_area = explode(",", $card_near->kitchen_area);
                                if (isset($kitchen_area[0]) && isset($kitchen_area[1]) && (int)$card->kitchen_area >= (int)$kitchen_area[0] && (int)$card->kitchen_area <= (int)$kitchen_area[1]) {
                                    $percent+=1;
                                }
                            } else {
                                $kitchen_area = explode(",", $card->kitchen_area);
                                if (isset($kitchen_area[0]) && isset($kitchen_area[1]) && (int)$kitchen_area[0] <= $card_near->kitchen_area && (int)$kitchen_area[1] >= (int)$card_near->kitchen_area) {
                                    $percent+=1;
                                }
                            }
                        }
                        //price
                        if (!is_null($card_near->price) && !is_null($card->price)) {
                            if ($card->sale_type === 'object') {
                                if ((float)$card->price <= (float)$card_near->price) {
                                    $percent+=1;
                                }
                            } else {
                                if ((float)$card->price >= (float)$card_near->price) {
                                    $percent+=1;
                                }
                            }
                        }

                    }
                }

                $card_near->percent = $percent;

                $card_near->CardContact;
                if (!empty($card_near->CardContact)) {
                    $card_near->CardContact->CardsContactsPhones;
                }

                $card_near->CardFiles;
                $card_near->CardAgency;
                $card_near->CardOffice;
                if ($card_near->CardUser) {
                    if ($card_near->CardUser->UserDetails) {
                        $card_near->CardUser->UserDetails->profileImage;
                    }
                    $card_near->CardUser->UserPhones;
                    $card_near->CardUser->UserSocials;
                }


                if (!empty($card_near->CardFiles)) {
                    foreach ($card_near->CardFiles as $cardFile) {
                        $cardFile->file;
                    }
                }
            }
        }
        return response()->json($cards, 200);
    }

    public function setStatusCardRequest(Request $request) {
        $card_request_id = $request->get('card_request_id');
        $card_object_id = $request->get('card_object_id');
        $status = $request->get('status');

        if (!$card_request_id || !$card_object_id || !$status) {
            return response()->json(array(), 402);
        }

        $card_request_status = CardRequestStatus::where('card_request_id', '=', $card_request_id)
            ->where('card_object_id','=', $card_object_id)->first();
        if ($card_request_status) {
            $card_request_status->status = $status;
            $card_request_status->user_id = $this->current_user_id;
            $card_request_status->save();
        } else {
            $data = [
                'status' => $status,
                'card_request_id' => $card_request_id,
                'card_object_id' => $card_object_id,
                'user_id' => $this->current_user_id,

            ];
            $card_request_status = CardRequestStatus::create($data);

        }
        return response()->json($card_request_status, 201);
    }

    public function getStatusCardRequest(Request $request) {
        $card_request_id = $request->get('card_request_id');
        $card_object_id = $request->get('card_object_id');

        if (!$card_request_id && !$card_object_id) {
            return response()->json(array(), 402);
        }

        $page = $request->get('page');
        $size = $request->get('size');
        if (!$page) {
            $page = 1;
        }

        if (!$size) {
            $size = 10;
        }

        $query = CardRequestStatus::query();

        if (isset($card_request_id)) {
            if (is_array($card_request_id) && !empty($card_request_id)) {
                $query->where('card_request_id', 'in', $card_request_id);
            } else {
                $query->where('card_request_id', '=', $card_request_id);
            }
        }

        if (isset($card_object_id)) {
            if (is_array($card_object_id) && !empty($card_object_id)) {
                $query->where('card_object_id', 'in', $card_object_id);
            } else {
                $query->where('card_object_id', '=', $card_object_id);
            }
        }

        $card_request_status = $query->offset($page * $size)->orderBy("created_at", 'desc')->paginate($size);

        return response()->json($card_request_status, 200);
    }

    public function setCardRequestPost(Request $request) {
        $card_request_id = $request->get('card_request_id');
        $card_object_id = $request->get('card_object_id');
        $post = $request->get('post');
        $initial_card = $request->get('initial_card');

        if (!$card_request_id || !$card_object_id || !$post || !$initial_card) {
            return response()->json(array(), 402);
        }

        $data = [
            'post' => $post,
            'initial_card' => $initial_card,
            'card_request_id' => $card_request_id,
            'card_object_id' => $card_object_id,
            'user_id' => $this->current_user_id,

        ];
        $card_request_post = CardRequestPosts::create($data);

        return response()->json($card_request_post, 201);
    }

    public function getCardRequestPosts(Request $request) {
        $card_request_id = $request->get('card_request_id');
        $card_object_id = $request->get('card_object_id');
        $initial_card = $request->get('initial_card');

        if (!$card_request_id && !$card_object_id && !$initial_card) {
            return response()->json(array(), 402);
        }

        $page = $request->get('page');
        $size = $request->get('size');
        if (!$page) {
            $page = 1;
        }

        if (!$size) {
            $size = 10;
        }

        $query = CardRequestPosts::query();

        if (isset($card_request_id)) {
            if (is_array($card_request_id) && !empty($card_request_id)) {
                $query->where('card_request_id', 'in', $card_request_id);
            } else {
                $query->where('card_request_id', '=', $card_request_id);
            }
        }

        if (isset($card_object_id)) {
            if (is_array($card_object_id) && !empty($card_object_id)) {
                $query->where('card_object_id', 'in', $card_object_id);
            } else {
                $query->where('card_object_id', '=', $card_object_id);
            }
        }

        if (isset($initial_card)) {
            $query->where('initial_card', '=', $initial_card);
        }

        $card_request_post = $query->offset($page * $size)->orderBy("created_at", 'desc')->paginate($size);

        return  $card_request_post;
    }

}
