<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class ApiRolesController extends Controller
{
    public function index()
    {
        return Role::all();
    }

    public function show($id)
    {
        return response()->json(Role::find($id), 201);
    }

    public function store(Request $request)
    {

        $role = Role::create($request->all());
        return response()->json($role, 201);
    }

    public function update(Request $request, Role $role)
    {
        $role->update($request->all());
        return response()->json($role, 200);
    }

    public function delete(Role $role)
    {
        $role->delete();
        return response()->json(null, 204);
    }
}
