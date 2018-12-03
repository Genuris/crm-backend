<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\Request;
use App\Models\File;

class ApiFileController extends BaseController
{
    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            /*$user =  \Auth::id();
            dd($user);
            if (is_null($user)) {
                return response()->json(array('error' => array('status' => 401, 'message' => 'Unauthorized. The user needs to be authenticated.', 'data' => $user)), 401);
            }*/
            return $next($request);
        });
    }


    public function postFile(Request $request)
    {
        $file_hash = '';
        if ($request->hasFile('file')) {
            $destinationPath = 'uploads/files'; // upload path
            $fileName = Input::file('file')->hashName();

            $file = new File();
            $file->name = Input::file('file')->getClientOriginalName();
            $file->extension = Input::file('file')->getClientOriginalExtension();
            $file->hash = Input::file('file')->hashName();
            $file->type = Input::file('file')->getMimeType();

            $upload_success = Input::file('file')->move($destinationPath, $fileName);

            if ($upload_success) {
                $file->save();
                $file_hash = $file->hash;
            } else {
                return response()->json(array('error' => array('status' => 400, 'message' => 'body error')), 400);
            }
        }
        if ($file_hash === '') {
            return response()->json(array('error' => array('status' => 400, 'message' => 'body error')), 400);
        }
        return response()->json(array('data' => array('file' => $file_hash), 'status' => 200), 200);
    }


}
