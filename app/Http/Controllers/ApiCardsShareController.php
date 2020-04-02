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

            $cards = Card::whereIn('id', $card_ids)->get();
            if (!empty($cards)) {
                foreach ($cards as $card) {

                    $files = $card->CardFiles;
                    if (!empty($files)) {
                        foreach ($files as $key => $cardFile) {
                            $files[$key] = $cardFile->file;
                        }
                    }

                    $share_cards_data[] = array(
                        'id' => $card->id,
                        'price' => $card->price,
                        'currency' => $card->currency,
                        'agency_id'=> $card->agency_id,
                        'user_id'=> $card->user_id,
                        'office_id'=> $card->office_id,
                        'type'=> $card->type,
                        'sale_type'=> $card->sale_type,
                        'city'=> $card->city,
                        'area'=> $card->area,
                        'street'=> $card->street,
                        'landmark'=> $card->landmark,
                        'owner_or_realtor'=> $card->owner_or_realtor,
                        'year_built'=> $card->year_built,
                        'floors_house'=> $card->floors_house,
                        'floors_house_end'=> $card->floors_house_end,
                        'number_rooms'=> $card->number_rooms,
                        'type_building'=> $card->type_building,
                        'roof'=> $card->roof,
                        'total_area'=> $card->total_area,
                        'complete_percent'=> $card->complete_percent,
                        'total_area_end'=> $card->total_area_end,
                        'living_area'=> $card->living_area,
                        'kitchen_area'=> $card->kitchen_area,
                        'ceiling_height'=> $card->ceiling_height,
                        'condition'=> $card->condition,
                        'heating'=> $card->heating,
                        'electricity'=> $card->electricity,
                        'water_pipes'=> $card->water_pipes,
                        'bathroom'=> $card->bathroom,
                        'sewage'=> $card->sewage,
                        'internet'=> $card->internet,
                        'gas'=> $card->gas,
                        'security'=> $card->security,
                        'land_area'=> $card->land_area,
                        'how_plot_fenced'=> $card->how_plot_fenced,
                        'entrance_door'=> $card->entrance_door,
                        'furniture'=> $card->furniture,
                        'window'=> $card->window,
                        'limes'=> $card->limes,
                        'garage_height'=> $card->garage_height,
                        'garage_length'=> $card->garage_length,
                        'garage_width'=> $card->garage_width,
                        'ceiling'=> $card->ceiling,
                        'basement'=> $card->basement,
                        'balcony'=> $card->balcony,
                        'corner'=> $card->corner,
                        'gate_height'=> $card->gate_height,
                        'gate_width'=> $card->gate_width,
                        'view_from_windows'=> $card->view_from_windows,
                        'garbage_chute'=> $card->garbage_chute,
                        'number_of_floors'=> $card->number_of_floors,
                        'number_of_floors_end'=> $card->number_of_floors_end,
                        'layout'=> $card->layout,
                        'reason_for_sale'=> $card->reason_for_sale,
                        'category'=> $card->category,
                        'subcategory'=> $card->subcategory,
                        'stage_transaction'=> $card->stage_transaction,
                        'is_archived'=> $card->is_archived,
                        'elevator'=> $card->elevator,
                        'apartment_type'=> $card->apartment_type,
                        'payments'=> $card->payments,
                        'household_appliances'=> $card->household_appliances,
                        'archive_date'=> $card->archive_date,
                        'will_live'=> $card->will_live,
                        'data_change_prices'=> $card->data_change_prices,
                        'floor_location'=> $card->floor_location,
                        'created_at'=> $card->created_at,
                        'files' => $files
                    );
                }
            }
        }

        $user = $share_card->User;
        if ($user) {
            if ($user->UserDetails) {
                $user->UserDetails->profileImage;
            }
            $user->UserPhones;
            $user->UserSocials;
        }

        return response()->json(array('user' => $user, 'content' => $share_cards_data), 200);
    }

    public function get($hash)
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

        return response()->json($card_ids, 200);
    }

    public function set(Request $request)
    {
        if (!$request->get('user_id') || !$request->get('card_id')) {
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
