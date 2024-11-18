<?php

namespace Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class API
{
    public function baererToken(Request $request)
    {
        $token = $request->bearerToken();
        if(!$token)
        {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        else return  $token;
    }
}
