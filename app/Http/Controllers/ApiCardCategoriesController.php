<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\CardCategories;

class ApiCardCategoriesController extends Controller
{

    public $permissions = array(
        'GET' => ['see' => ['api/card_categories']],
        'PUT' => ['edit' => ['api/card_categories']],
        'POST' => ['add' => ['api/card_categories']],
        'DELETE' => ['delete' => ['api/card_categories']],
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

            if (!$role->checkAction($request->path(), $request->method(), $this->permissions, new CardCategories())) {
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
        return CardCategories::offset($page * $size)->orderBy("created_at", 'desc')->paginate($size);
    }

    public function show($id)
    {
        return response()->json(CardCategories::find($id), 200);
    }

    public function store(Request $request)
    {

        $card_category = CardCategories::create($request->all());
        return response()->json($card_category, 201);
    }

    public function update(Request $request, $id)
    {
        $card_category = CardCategories::find($id);
        if (!$card_category) {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }
        $card_category->update($request->all());
        return response()->json($card_category, 200);
    }

    public function delete(Request $request, $id) {
        $card_category = CardCategories::find($id);

        if ($card_category) {
            $card_category->delete();
        }

        return response()->json(null, 204);
    }
}
