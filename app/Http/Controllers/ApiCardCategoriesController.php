<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardCategories;

class ApiCardCategoriesController extends Controller
{
    public function index()
    {
        return CardCategories::all();
    }

    public function show($id)
    {
        return response()->json(CardCategories::find($id), 200);
    }

    public function store(Request $request)
    {

        $card_category = CardCategories::create($request->all());
        return response()->json($card_category, 201);
    }

    public function update(Request $request, CardCategories $card_category)
    {
        $card_category->update($request->all());
        return response()->json($card_category, 200);
    }

    public function delete(CardCategories $card_category)
    {
        $card_category->delete();
        return response()->json(null, 204);
    }
}
