<?php

namespace App\Http\Controllers;

use App\Models\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name"     => "required|string",
            "email"    => "required|string|email|unique:users",
            "password" => "required|string|min:4|confirmed",
        ]);
        if ($validator->fails()) {
            return Response::error(422, $validator->errors()->first());
        }
        try {
            $user = User::create($request->all());
            return Response::success(201, "User registerd", $user);
        } catch (\Throwable $th) {
            return Response::error(500, $th->getMessage());
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return Response::error(401, "Invalid credentials");
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