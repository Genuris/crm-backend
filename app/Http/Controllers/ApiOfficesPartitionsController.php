<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\OfficesPartition;

class ApiOfficesPartitionsController extends Controller
{
    public $permissions = array(
        'GET' => ['see' => ['api/offices_partitions']],
        'PUT' => ['update' => ['api/offices_partitions']],
        'POST' => ['add' => ['api/offices_partitions']],
        'DELETE' => ['delete' => ['api/offices_partitions']],
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

            if (!$role->checkAction($request->path(), $request->method(), $this->permissions, new OfficesPartition())) {
                return response()->json(array('error' => array('status' => 403, 'message' => 'Forbidden. The user is authenticated, but does not have the permissions to perform an action.')), 403);
            }

            return $next($request);
        });
    }

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

    public function update(Request $request, $id)
    {
        $offices_partition = OfficesPartition::find($id);
        if (!$offices_partition) {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }
        $offices_partition->update($request->all());
        return response()->json($offices_partition, 200);
    }

    public function delete(Request $request, $id) {
        $offices_partition = OfficesPartition::find($id);

        if ($offices_partition) {
            $offices_partition->delete();
        }

        return response()->json(null, 204);
    }
}
