<?php

namespace App\Http\Controllers;

use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return Response::error(401, "Unauthorized");
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return Response::success(200, "Get my information", Auth::user());
    }

    public function logout()
    {
        Auth::logout();
        return  Response::success(200, "Logout success");
    }

    protected function respondWithToken($token)
    {
        # result format
        $data = [
            'token_type' => 'bearer',
            'access_token' => $token,
            'expires_in' => Auth::factory()->getTTL() * 60 * 24,
            'user' => Auth::user()
        ];

        return Response::success(200, "Login success", $data);
    }
}
