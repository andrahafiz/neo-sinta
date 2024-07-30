<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RegisterRequest;
use App\Http\Resources\AdminResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->all();

        $data['password'] = bcrypt($request->password);

        $admin = User::create($data);
        $admin->assignRole(['admin']);

        $token = $admin->createToken('API Token')->accessToken;
        Log::info('REGISTER USER', ['data' => $admin]);
        return Response::json(
            [
                'message' => 'Registration Successful',
                'user' => $admin,
                'token' => $token
            ],
            Response::MESSAGE_OK,
            Response::STATUS_OK
        );
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            //generate the token for the user
            $token = auth()->user()->createToken('authToken')->accessToken;

            return Response::json([
                'user' => auth()->user(),
                'token' => $token
            ]);
        } else {
            return Response::json(
                [
                    'message' => "Invalid Grant",
                    "details" => "The user credentials were incorrect"
                ],
                Response::MESSAGE_UNAUTHORIZED,
                Response::STATUS_UNAUTHORIZED
            );
        }
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
