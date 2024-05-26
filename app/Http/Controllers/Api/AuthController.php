<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $data = $request->all();

        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        $token = $user->createToken('API Token')->accessToken;
        Log::info('REGISTER USER', ['data' => $user]);
        return Response::json(
            [
                'message' => 'Registration Successful',
                'user' => new UserResource($user),
                'token' => $token
            ],
            Response::MESSAGE_OK,
            Response::STATUS_OK
        );
    }

    public function login(LoginRequest $request)
    {
        $data = $request->all();

        if (!auth()->attempt($data)) {
            return Response::json(
                [
                    'message' => "Invalid Grant",
                    "details" => "The user credentials were incorrect"
                ],
                Response::MESSAGE_UNAUTHORIZED,
                Response::STATUS_UNAUTHORIZED
            );
        }

        $token = auth()->user()->createToken('API Token')->accessToken;
        Log::info('LOGIN USER', ['data' => auth()->user(), 'loginAt' => now()]);
        return Response::json([
            'user' => new UserResource(auth()->user()),
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        Log::info('LOGOUT USER', ['data' => auth()->user(), 'logoutAt' => now()]);
        $token = $request->user()->token();
        $token->revoke();

        return Response::json([
            'message' => 'Token Revoked Successfully.'
        ]);
    }
}
