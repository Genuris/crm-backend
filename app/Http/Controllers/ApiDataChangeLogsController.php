<?php

namespace App\Http\Controllers;

use App\Models\DataChangeLogs;
use Illuminate\Http\Request;
use App\Models\Role;

class ApiDataChangeLogsController extends Controller
{
    public $permissions = array(
        'GET' => ['see' => ['api/data_change_logs']]
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

            if (!$role->checkAction($request->path(), $request->method(), $this->permissions, new DataChangeLogs())) {
                return response()->json(array('error' => array('status' => 403, 'message' => 'Forbidden. The user is authenticated, but does not have the permissions to perform an action.')), 403);
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $item_id = $request->get('item_id');
        $user_id = $request->get('user_id');
        $object = $request->get('object');
        $page = $request->get('page');
        $size = $request->get('size');

        $query = DataChangeLogs::query();
        if ($item_id) {
            $query->where('item_id', '=', $item_id);
        }

        if ($user_id) {
            $query->where('user_id', '=', $user_id);
        }

        if ($object) {
            $query->where('object', 'like', $object);
        }

        if (!$page) {
            $page = 1;
        }

        if (!$size) {
            $size = 10;
        }

        $data_change_logs = $query->offset($page * $size)->orderBy("created_at", 'desc')->paginate($size);

        if (!empty($data_change_logs)) {
            foreach($data_change_logs as $data_change_log) {
                $data_change_log->DataChangeLogsUser;
            }
        }

        return $data_change_logs;
    }
}
