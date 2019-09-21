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

            if ($user->is_archived === 1) {
                return response()->json(array('error' => array('status' => 401, 'message' => 'User is deleted')), 401);
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

        $sort = explode(',', $request->get('sort'));

        if (!$page) {
            $page = 1;
        }

        if (!$size) {
            $size = 10;
        }

        $flag = false;
        if (is_array($sort) and count($sort) > 1) {
            $object = new CardCategories();
            if (in_array($sort[0], $object->getFields()) && in_array($sort[1], array('desc', 'asc'))) {
                $flag = true;
            }
        }

        if ($flag) {
            return CardCategories::offset($page * $size)->orderBy($sort[0], $sort[1])->paginate($size);
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
