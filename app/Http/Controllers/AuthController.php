<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 400,
                "message" => $validator->errors()->all(),
            ], 400);
        }

        DB::beginTransaction();

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user-> remember_token = Str::random();
        $user->role = "admin";
        $user->save();

        DB::commit();

        return response()->json([
            "status" => 200,
            "message" => "success"
        ]);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 400,
                "message" => $validator->errors()->all(),
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('token')->accessToken;

                return response()->json([
                    "status" => 200,
                    "user" => $user,
                    "token"=> $token
                ]);
            } else {
                return response()->json([
                    "status" => 400,
                    "message" => "Password mismatch"
                ]);
            }
        } else {
            return response()->json([
                "status" => 400,
                "message" => "User does not exist"
            ]);
        }
    }

    public function logout(Request $request) {
        $token = $request->user()->token();
        $token->revoke();

        return response()->json([
            "status" => 200,
            "message" => "success"
        ]);
    }
}
