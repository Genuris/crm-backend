<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;

class ApiOfficesController extends Controller
{
    public function index()
    {
        return Office::all();
    }

    public function show($id)
    {
        return response()->json(Office::find($id), 200);
    }

    public function store(Request $request)
    {

        $office = Office::create($request->all());
        return response()->json($office, 201);
    }

    public function update(Request $request, $id)
    {
        $office = Office::find($id);
        if (!$office) {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }
        $office->update($request->all());
        return response()->json($office, 200);
    }

    public function delete(Request $request, $id) {
        $office = Office::find($id);

        if ($office) {
            $office->delete();
        }

        return response()->json(null, 204);
    }

}
