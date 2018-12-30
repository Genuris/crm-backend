<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\CardsFile;
use App\Models\CardContacts;
use App\Models\CardContactsPhones;

class ApiCardsController extends Controller
{
    public function index()
    {
        return Card::all();
    }

    public function show($id)
    {
        return response()->json(Card::find($id), 201);
    }

    public function store(Request $request)
    {
        $cards_contacts_id = null;

        if (!empty($request->get('card_contact')) && is_array($request->get('card_contact'))) {

            $card_contact_data = $request->get('card_contact');

            if (isset($card_contact_data['cards_contacts_phones']) and is_array($card_contact_data['cards_contacts_phones'])) {

                foreach ($card_contact_data['cards_contacts_phones'] as $key => $value) {

                    $card_contacts_phone = CardContactsPhones::where('phone', 'like', $value['phone'])->first();

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
                    'email' => (isset($card_contact_data['email']) ? $card_contact_data['email'] : '')
                ]);

                if (!$card_contact) {
                    return response()->json(array(), 402);
                }

                $cards_contacts_id = $card_contact->id;

                foreach ($card_contact_data['cards_contacts_phones'] as $key => $value) {

                    CardContactsPhones::create([
                        'cards_contacts_id' => $cards_contacts_id,
                        'phone' => (isset($value['phone']) ? $value['phone'] : '')
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
        $card_data['year_built'] = (int)$card_data['year_built'];
        $card_data['number_rooms'] = (int)$card_data['number_rooms'];
        $card_data['number_contract'] = (int)$card_data['number_contract'];
        $card_data['contract_expiration_date'] = (int)$card_data['contract_expiration_date'];

        $card_data['garbage_chute'] = ((isset($card_data['garbage_chute']) && $card_data['garbage_chute'] == 'true') ? true : false);
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
        $card->CardFiles;
        $card->CardAgency;
        $card->CardOffice;
        $card->CardUser;

        return response()->json($card, 201);
    }

    public function update(Request $request, $id)
    {
        $card = Card::findOrFail($id);
        if ($card) {

            $card_data = $request->all();

            unset($card_data['card_contact']);
            unset($card_data['cards_file']);

            $card_data['user_id'] = (int)$card_data['user_id'];
            $card_data['agency_id'] = (int)$card_data['agency_id'];
            $card_data['office_id'] = (int)$card_data['office_id'];
            $card_data['year_built'] = (int)$card_data['year_built'];
            $card_data['number_rooms'] = (int)$card_data['number_rooms'];
            $card_data['number_contract'] = (int)$card_data['number_contract'];
            $card_data['contract_expiration_date'] = (int)$card_data['contract_expiration_date'];

            $card_data['garbage_chute'] = ((isset($card_data['garbage_chute']) && $card_data['garbage_chute'] == 'true') ? true : false);
            $card_data['is_archived'] = ((isset($card_data['is_archived']) && $card_data['is_archived'] == 'true') ? true : false);

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
        $card->CardFiles;
        $card->CardAgency;
        $card->CardOffice;
        $card->CardUser;

        return response()->json($card, 201);
    }

    public function delete(Card $card)
    {
        $card->delete();
        return response()->json(null, 204);
    }
}
