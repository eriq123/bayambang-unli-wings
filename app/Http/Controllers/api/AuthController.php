<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/register",
     *      operationId="registerUser",
     *      tags={"Authentication"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={
     *                  "name",
     *                  "email",
     *                  "password"
     *              },
     *              @OA\Property(property="name", type="string", format="text", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="email", example="johnDoe@mail.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password123"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string", description="Bearer token"),
     *          ),
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return $user->createToken($request->email)->plainTextToken;
    }

    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="loginUser",
     *      tags={"Authentication"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={
     *                  "email",
     *                  "password"
     *              },
     *              @OA\Property(property="email", type="string", format="email", example="johnDoe@mail.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password123"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string", description="Bearer token"),
     *          ),
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
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

    /**
     * @OA\Get(
     *      path="/user",
     *      operationId="getUser",
     *      tags={"User"},
     *      security={ {"sanctum": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="user", type="object", description="User object."),
     *          ),
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * @OA\Post(
     *      path="/logout",
     *      operationId="logoutUser",
     *      tags={"Authentication"},
     *      security={ {"sanctum": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="user", type="object", description="User object."),
     *          ),
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json($user);
    }

    /**
     * @OA\Post(
     *      path="/user/address",
     *      operationId="updateUserAddress",
     *      tags={"User"},
     *      security={ {"sanctum": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={
     *                  "address",
     *              },
     *              @OA\Property(property="address", type="string", format="text", example="7002 Roscoe Mountains Suite 763 Waltermouth, TN 99169"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="user", type="object", description="User object."),
     *          ),
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
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

    /**
     * @OA\Get(
     *      path="/user/address",
     *      operationId="getUserAddress",
     *      tags={"User"},
     *      security={ {"sanctum": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="address", type="string", description="User address."),
     *          ),
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function getAddress(Request $request)
    {
        return response()->json([
            'address' => $request->user()->address,
        ]);
    }
}
