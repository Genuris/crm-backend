<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\SocialNetworks;
use Illuminate\Http\Request;

class ApiSocialNetworksController extends Controller
{
    public $permissions = array(
        'GET' => ['see' => ['api/social_networks']],
        'PUT' => ['update' => ['api/social_networks']],
        'POST' => ['add' => ['api/social_networks']],
        'DELETE' => ['delete' => ['api/social_networks']],
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

            if (!$role->checkAction($request->path(), $request->method(), $this->permissions, new SocialNetworks())) {
                return response()->json(array('error' => array('status' => 403, 'message' => 'Forbidden. The user is authenticated, but does not have the permissions to perform an action.')), 403);
            }

            return $next($request);
        });
    }

    public function index()
    {
        return SocialNetworks::all();
    }

    public function show($id)
    {
        return response()->json(SocialNetworks::find($id), 200);
    }

    public function store(Request $request)
    {

        $social_network = SocialNetworks::create($request->all());
        return response()->json($social_network, 201);
    }

    public function update(Request $request, $id)
    {
        $social_network = SocialNetworks::find($id);
        if (!$social_network) {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }
        $social_network->update($request->all());
        return response()->json($social_network, 200);
    }

    public function delete(Request $request, $id) {
        $social_network = SocialNetworks::find($id);

        if ($social_network) {
            $social_network->delete();
        }

        return response()->json(null, 204);
    }
}
