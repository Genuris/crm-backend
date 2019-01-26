<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;

class ApiCurrenciesController extends Controller
{
    public function index()
    {
        return Currency::all();
    }

    public function show($id)
    {
        return response()->json(Currency::find($id), 200);
    }

    public function store(Request $request)
    {

        $currency = Currency::create($request->all());
        return response()->json($currency, 201);
    }

    public function update(Request $request, $id)
    {
        $currency = Currency::find($id);
        if (!$currency) {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }
        $currency->update($request->all());
        return response()->json($currency, 200);
    }

    public function delete(Request $request, $id) {
        $currency = Currency::find($id);

        if ($currency) {
            $currency->delete();
        }

        return response()->json(null, 204);
    }

}
