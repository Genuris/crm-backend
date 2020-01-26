<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use App\Models\CardShare;

class ApiCardsShareController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if ($request->method() === 'POST') {
                $user = $request->user();

                if (!$user) {
                    return response()->json(array('error' => array('status' => 401, 'message' => 'Unauthorized. The user needs to be authenticated.')), 401);
                }

                if ($user->is_archived === 1) {
                    return response()->json(array('error' => array('status' => 401, 'message' => 'User is deleted')), 401);
                }
            }

            return $next($request);
        });
    }

    public function show($hash)
    {
        if (!$hash) {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }

        $share_card = CardShare::where('hash', '=', $hash)->first();
        if (!$share_card) {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }

        $card_ids =  json_decode($share_card->cards, true);

        $share_cards_data = [];
        if (is_array($card_ids) && !empty($card_ids)) {

            $cards = Card::where('id', 'in', $card_ids)->get();
            if (!empty($cards)) {
                foreach ($cards as $card) {
                    $share_cards_data[] = array(
                        'id' => $card->id,
                        'price' => $card->price,
                        'currency' => $card->currency,
                        'files' => $card->CardFiles
                    );
                }
            }
        }

        return response()->json($share_cards_data, 200);
    }

    public function set(Request $request)
    {
        if (!$request->get('user_id') || !$request->get('card_id') || !$request->get('cards')) {
            return response()->json(array(), 402);
        }

        if (!is_array($request->get('cards'))) {
            return response()->json(array(), 402);
        }
        $user_id = $request->get('user_id');
        $card_id = $request->get('card_id');
        $cards = $request->get('cards');

        $card_share = CardShare::where('user_id', '=', $user_id)->where('card_id', '=', $card_id)->first();
        if ($card_share) {
            $card_share->cards = json_encode($cards);
            $card_share->save();
        } else {
            $card_share = CardShare::create(array(
                'user_id' => $user_id,
                'card_id' => $card_id,
                'cards' => json_encode($cards),
                'hash' => md5($user_id.'_'.$card_id)
            ));
            if (!$card_share) {
                return response()->json(array(), 402);
            }
        }
        return response()->json($card_share, 201);

    }

}
