<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OfficesPartition;

class ApiOfficesPartitionsController extends Controller
{
    public function index()
    {
        return OfficesPartition::all();
    }

    public function show($id)
    {
        return response()->json(OfficesPartition::find($id), 200);
    }

    public function store(Request $request)
    {

        $offices_partition = OfficesPartition::create($request->all());
        return response()->json($offices_partition, 201);
    }

    public function update(Request $request, OfficesPartition $offices_partition)
    {
        $offices_partition->update($request->all());
        return response()->json($offices_partition, 200);
    }

    public function delete(OfficesPartition $offices_partition)
    {
        $offices_partition->delete();
        return response()->json(null, 204);
    }
}
