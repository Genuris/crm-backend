<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class ApiRolesController extends Controller
{
    public $permissions = array(
        'GET' => ['see' => ['api/roles']],
        'PUT' => ['edit' => ['api/roles']],
        'POST' => ['add' => ['api/roles']],
        'DELETE' => ['delete' => ['api/roles']],
    );

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $user = $request->user();

            if (!$user) {
                return response()->json(array('error' => array('status' => 401, 'message' => 'Unauthorized. The user needs to be authenticated.')), 401);
            }

            $role = Role::find($user->role_id);

            if (!$role) {
                return response()->json(array('error' => array('status' => 401, 'message' => 'Unauthorized. The user needs to be authenticated.')), 401);
            }

            if (!$role->checkAction($request->path(), $request->method(), $this->permissions, new Role())) {
                return response()->json(array('error' => array('status' => 403, 'message' => 'Forbidden. The user is authenticated, but does not have the permissions to perform an action.')), 403);
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $page = $request->get('page');
        $size = $request->get('size');

        if (!$page) {
            $page = 1;
        }

        if (!$size) {
            $size = 10;
        }
        return Role::offset($page * $size)->orderBy("created_at", 'desc')->paginate($size);
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
