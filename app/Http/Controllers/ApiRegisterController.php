<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiRegisterController extends Controller
{
    public function store(Request $request)
    {
//        dd(Auth::check());
        //dd(Auth::guard('api')->user()->role_id);
        if (empty($request->get('name')) || is_null($request->get('name'))) {
            return response()->json(array('error' => array('status' => 400, 'message' => 'name is empty')), 400);
        }

        if (empty($request->get('email')) || is_null($request->get('email'))) {
            return response()->json(array('error' => array('status' => 400, 'message' => 'email is empty')), 400);
        }

        if (empty($request->get('password')) || is_null($request->get('password'))) {
            return response()->json(array('error' => array('status' => 400, 'message' => 'password is empty')), 400);
        }

        if (empty($request->get('role_id')) || is_null($request->get('role_id'))) {
            return response()->json(array('error' => array('status' => 400, 'message' => 'role_id is empty')), 400);
        }

        $role = Role::find($request->get('role_id'));

        if (!$role) {
            return response()->json(array('error' => array('status' => 400, 'message' => 'role is missing')), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'role_id' => $request->get('role_id'),
            'password' => bcrypt($request->get('password')),
        ]);
        return response()->json($user, 201);
    }
}
