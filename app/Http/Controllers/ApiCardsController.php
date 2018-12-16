<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;

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

        $card = Card::create($request->all());
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
