<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return response()->json('', Response::HTTP_NO_CONTENT );
        }else{
            return response()->json([
                'error' => 'invalid_credentials'
            ], Response::HTTP_FORBIDDEN);
        }
    }
}
