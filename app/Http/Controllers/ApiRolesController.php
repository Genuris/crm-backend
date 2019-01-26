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
        return response()->json(Role::find($id), 200);
    }

    public function store(Request $request)
    {
        $role = Role::create($request->all());
        return response()->json($role, 201);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }
        $role->update($request->all());
        return response()->json($role, 200);
    }

    public function delete(Request $request, $id) {
        $role = Role::find($id);

        if ($role) {
            $role->delete();
        }

        return response()->json(null, 204);
    }
}
