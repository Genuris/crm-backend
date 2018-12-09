<?php

namespace App\Http\Controllers;

use App\Models\SocialNetworks;
use Illuminate\Http\Request;

class ApiSocialNetworksController extends Controller
{
    public function index()
    {
        return SocialNetworks::all();
    }

    public function show($id)
    {
        return response()->json(SocialNetworks::find($id), 201);
    }

    public function store(Request $request)
    {

        $social_network = SocialNetworks::create($request->all());
        return response()->json($social_network, 201);
    }

    public function update(Request $request, SocialNetworks $social_network)
    {
        $social_network->update($request->all());
        return response()->json($social_network, 200);
    }

    public function delete(SocialNetworks $social_network)
    {
        $social_network->delete();
        return response()->json(null, 204);
    }
}
