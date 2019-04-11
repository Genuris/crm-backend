<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Currency;

class ApiCurrenciesController extends Controller
{
    public $permissions = array(
        'GET' => ['see' => ['api/currencies']],
        'PUT' => ['edit' => ['api/currencies']],
        'POST' => ['add' => ['api/currencies']],
        'DELETE' => ['delete' => ['api/currencies']],
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

            if (!$role->checkAction($request->path(), $request->method(), $this->permissions, new Currency())) {
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
        return Currency::offset($page * $size)->orderBy("created_at", 'desc')->paginate($size);
    }

    public function show($id)
    {
        return response()->json(Currency::find($id), 200);
    }

    public function store(Request $request)
    {

        $currency = Currency::create($request->all());
        return response()->json($currency, 201);
    }

    public function update(Request $request, $id)
    {
        $currency = Currency::find($id);
        if (!$currency) {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }
        $currency->update($request->all());
        return response()->json($currency, 200);
    }

    public function delete(Request $request, $id) {
        $currency = Currency::find($id);

        if ($currency) {
            $currency->delete();
        }

        return response()->json(null, 204);
    }

}
