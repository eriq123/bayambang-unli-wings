<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register()
    {
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The credentials you provided is incorrect.'
            ], 401);
        }

        return $user->createToken($request->email)->plainTextToken;
    }

    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json($user);
    }

    public function updateAddress(Request $request)
    {
        $request->validate([
            'address' => ['required'],
        ]);

        $user = User::find($request->user()->id);
        $user->address = $request->address;
        $user->save();

        return response()->json(compact('user'));
    }

    public function getAddress(Request $request)
    {
        return response()->json([
            'address' => $request->user()->address,
        ]);
    }
}
