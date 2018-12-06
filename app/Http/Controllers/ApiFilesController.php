<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Input;

class ApiFilesController extends Controller
{

    public function store(Request $request)
    {
        if ($request->hasFile('file')) {
            $destinationPath = 'uploads/files';
            $fileName = Input::file('file')->hashName();
            $file = new File();
            $file->name = Input::file('file')->getClientOriginalName();
            $file->extension = Input::file('file')->getClientOriginalExtension();
            $file->hash = Input::file('file')->hashName();
            $file->type = Input::file('file')->getMimeType();
            $upload_success = Input::file('file')->move($destinationPath, $fileName);
            if ($upload_success) {
                $file->save();
                return response()->json($file, 201);
            }
        }
        return response()->json(array('error' => array('status' => 400, 'message' => 'body error')), 400);
    }

    public function delete($id)
    {
        $file = File::find($id);
        if ($file) {
            $file->delete();
            return response()->json(null, 204);
        }
        return response()->json(array('error' => array('status' => 400, 'message' => 'body error')), 400);
    }
}
