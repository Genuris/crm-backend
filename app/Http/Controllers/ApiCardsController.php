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

                    $card_contacts_phone = CardContactsPhones::where('phone', '=', $value['phone'])->first();

                    if ($card_contacts_phone) {

                        $cards_contacts_id = $card_contacts_phone->id;

                    } else {

                        $card_contact = CardContacts::create([
                            'name' => (isset($card_contact_data['name']) ? $card_contact_data['name'] : ''),
                            'email' => (isset($card_contact_data['email']) ? $card_contact_data['email'] : '')
                        ]);

                        if (!$card_contact) {
                            return response()->json(array(), 402);
                        }

                        $cards_contacts_id = $card_contact->id;

                    }

                }

            } else {

                return response()->json(array(), 402);

            }

        }

        if (!$cards_contacts_id) {
            return response()->json(array(), 402);
        }

        $card_data = $request->all();
        $card_data['cards_contacts_id'] = $cards_contacts_id;

        $card = Card::create($card_data);

        if ($card) {

            if (!empty($request->get('cards_file')) && is_array($request->get('cards_file'))) {

                $cards_file_data = $request->get('cards_file');

                foreach($cards_file_data as $key => $value) {

                    CardsFile::create([
                        'card_id' => $card->id,
                        'type' => (isset($value['type']) ? $value['type'] : ''),
                        'email' => (isset($value['email']) ? $value['email'] : '')
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

    public function update(Request $request, Card $card)
    {
        $card->update($request->all());
        return response()->json($card, 200);
    }

    public function delete(Card $card)
    {
        $card->delete();
        return response()->json(null, 204);
    }
}
