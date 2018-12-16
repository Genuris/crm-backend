<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agency;

class ApiAgenciesController extends Controller
{
    public function index()
    {
        return Agency::all();
    }

    public function show($id)
    {
        return response()->json(Agency::find($id), 201);
    }

    public function store(Request $request)
    {

        $agency = Agency::create($request->all());
        return response()->json($agency, 201);
    }

    public function update(Request $request, Agency $agency)
    {
        $agency->update($request->all());
        return response()->json($agency, 200);
    }

    public function delete(Agency $agency)
    {
        $agency->delete();
        return response()->json(null, 204);
    }
}
