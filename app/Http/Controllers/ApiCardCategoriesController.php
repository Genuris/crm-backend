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

    public function update(Request $request, $id)
    {
        $card_category = CardCategories::find($id);
        if (!$card_category) {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }
        $card_category->update($request->all());
        return response()->json($card_category, 200);
    }

    public function delete(Request $request, $id) {
        $card_category = CardCategories::find($id);

        if ($card_category) {
            $card_category->delete();
        }

        return response()->json(null, 204);
    }
}
