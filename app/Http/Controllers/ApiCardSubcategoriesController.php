<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardSubcategories;

class ApiCardSubcategoriesController extends Controller
{
    public function index()
    {
        return CardSubcategories::all();
    }

    public function show($id)
    {
        return response()->json(CardSubcategories::find($id), 200);
    }

    public function store(Request $request)
    {

        $card_sub_category = CardSubcategories::create($request->all());
        return response()->json($card_sub_category, 201);
    }

    public function update(Request $request, $id)
    {
        $card_sub_category = CardSubcategories::find($id);
        if (!$card_sub_category) {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }
        $card_sub_category->update($request->all());
        return response()->json($card_sub_category, 200);
    }

    public function delete(Request $request, $id) {
        $card_sub_category = CardSubcategories::find($id);

        if ($card_sub_category) {
            $card_sub_category->delete();
        }

        return response()->json(null, 204);
    }
}
