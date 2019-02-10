<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\CardSubcategories;

class ApiCardSubcategoriesController extends Controller
{
    public $permissions = array(
        'GET' => ['see' => ['api/card_subcategories']],
        'PUT' => ['update' => ['api/card_subcategories']],
        'POST' => ['add' => ['api/card_subcategories']],
        'DELETE' => ['delete' => ['api/card_subcategories']],
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

            if (!$role->checkAction($request->path(), $request->method(), $this->permissions, new CardSubcategories())) {
                return response()->json(array('error' => array('status' => 403, 'message' => 'Forbidden. The user is authenticated, but does not have the permissions to perform an action.')), 403);
            }

            return $next($request);
        });
    }

    public function index()
    {
        return CardSubcategories::all();
    }

    public function show($id)
    {
        return response()->json(CardSubcategories::find($id), 200);
    }

    public function store(Request $request)
    {

        $card_sub_category = CardSubcategories::create($request->all());
        return response()->json($card_sub_category, 201);
    }

    public function update(Request $request, $id)
    {
        $card_sub_category = CardSubcategories::find($id);
        if (!$card_sub_category) {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }
        $card_sub_category->update($request->all());
        return response()->json($card_sub_category, 200);
    }

    public function delete(Request $request, $id) {
        $card_sub_category = CardSubcategories::find($id);

        if ($card_sub_category) {
            $card_sub_category->delete();
        }

        return response()->json(null, 204);
    }
}
