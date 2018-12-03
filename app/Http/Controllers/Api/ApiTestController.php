<?php

namespace App\Http\Controllers\Api;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;

class ApiTestController extends BaseController
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            /*$authorization_bearer = $request->header('Authorization');
            $authorization_token = 'Bearer '. env('API_TOKEN');

            if (is_null($authorization_bearer) || $authorization_token != $authorization_bearer) {
                return response()->json(array('error' => array('status' => 401, 'message' => 'Unauthorized. The user needs to be authenticated.')), 401);
            }*/
            return $next($request);
        });
    }


    public function checkApi(Request $request) {
        /*$token = $request->get('token');
        if (is_null($token)) {
            return response()->json(array('error' => array('status' => 400, 'message' => 'body error')), 400);
        }
        $user = User::where('api_key', '=', $token)->first();
        if (!$user) {
            return response()->json(array('error' => array('status' => 400, 'message' => 'user not found')), 400);
        }*/
        return response()->json(array('data' => array(), 'status' => 200), 200);
    }



}
