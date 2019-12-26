<?php

namespace App\Http\Controllers;

use App\Models\ParserCard;
use App\Models\ParserCardPhones;
use App\Models\Role;
use Illuminate\Http\Request;

class ApiParserCardsController extends Controller
{
    public $permissions = array(
        'GET' => ['see' => ['api/tasks']],
        'PUT' => ['edit' => ['api/tasks']],
        'POST' => ['add' => ['api/tasks']],
        'DELETE' => ['delete' => ['api/tasks']],
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

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $page = $request->get('page');
        $size = $request->get('size');

        $area = $request->get('area');
        $price_from = $request->get('price_from');
        $price_to = $request->get('price_to');

        if (!$page) {
            $page = 1;
        }

        if (!$size) {
            $size = 10;
        }

        $query = ParserCard::query();

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
                            $q->where('area', 'REGEXP', '\b('.$areas_search_array.')\b');
                            $q->orWhereNull('area');
                        });
                    } else {
                        $query->where('area', 'REGEXP', '\b('.$areas_search_array.')\b');
                    }
                } else if(count($areas_search_array) > 0){
                    if ($flagIsNull) {
                        $query->where(function ($q) use ($areas_search_array) {
                            $q->where('area', 'REGEXP', '\b('.$areas_search_array[0].')\b');
                            $q->orWhereNull('area');
                        });
                    } else {
                        $query->where('area', 'REGEXP', '\b('.$areas_search_array.')\b');
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
                    $query->where('area', 'REGEXP', '\b('.$area.')\b');
                }
            }
        }

        if ($price_from) {
            $query->where('price', '>=', (float)$price_from);
        }

        if ($price_to) {
            $query->where('price', '<=', (float)$price_to);
        }

        $parser_cards = $query->offset($page * $size)->orderBy("created_at", 'desc')->paginate($size);

        if (!empty($parser_cards)) {
            foreach ($parser_cards as $parser_card) {
                $parser_card->ParserCardsPhones;

                $parser_card->ParserCardsPhones;
                if (!empty($parser_card->ParserCardsPhones)) {
                    foreach ($parser_card->ParserCardsPhones as $parserCardsPhones) {
                        $parserCardsPhones->ContactPhone;
                        if (!empty($parserCardsPhones->ContactPhone)) {
                            $parserCardsPhones->ContactPhone->contact;
                        }
                    }
                }
            }
        }

        return $parser_cards;
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if (isset($data['content']) && is_array($data['content'])) {

            ParserCard::query()->delete();

            foreach ($data['content'] as $parser_card_data) {

                $parser_card = ParserCard::create($parser_card_data);

                if ($parser_card && isset($parser_card_data['phones']) && is_array($parser_card_data['phones'])) {

                    foreach ($parser_card_data['phones'] as $card_phone) {
                        ParserCardPhones::create(array(
                            'phone' => $card_phone,
                            'parser_cards_id' => $parser_card->id
                        ));
                    }

                }

            }

        }

        return response()->json($data['content'], 201);
    }

    public function update(Request $request, $id)
    {
        $comment = $request->get('comment');
        if (!$comment) {
            return response()->json(array(), 402);
        }

        $parser_card = ParserCard::find($id);
        if (!$parser_card) {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }
        $parser_card->comment = $comment;
        $parser_card->save();
        return response()->json($parser_card, 200);
    }

    public function delete(Request $request, $id) {
        $parser_card = ParserCard::find($id);
        if ($parser_card) {
            $parser_card_phones = ParserCardPhones::where('parser_cards_id', '=', $id)->get();
            if (!empty($parser_card_phones)) {
                foreach($parser_card_phones as $parser_card_phone) {
                    $parser_card_phone->delete();
                }
            }
            $parser_card->delete();
        }
        return response()->json(null, 204);
    }
}
