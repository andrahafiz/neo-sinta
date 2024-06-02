<?php

namespace App\Http\Controllers\Api;

use App\Models\Mahasiswa;
use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\MahasiswaResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $data = $request->all();

        $data['password'] = bcrypt($request->password);

        $mahasiswa = Mahasiswa::create($data);

        $token = $mahasiswa->createToken('API Token')->accessToken;
        Log::info('REGISTER USER', ['data' => $mahasiswa]);
        return Response::json(
            [
                'message' => 'Registration Successful',
                'user' => new MahasiswaResource($mahasiswa),
                'token' => $token
            ],
            Response::MESSAGE_OK,
            Response::STATUS_OK
        );
    }

    public function login(LoginRequest $request)
    {
        config(['auth.guards.mahasiswa-guard.driver' => 'session']);
        if (Auth::guard('mahasiswa-guard')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            //generate the token for the user
            $token = auth()->guard('mahasiswa-guard')->user()
                ->createToken('authToken')->accessToken;

            return Response::json([
                'user' => auth()->guard('mahasiswa-guard')->user(),
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
        Log::info('LOGOUT USER', ['data' => auth()->guard('mahasiswa-guard')->user(), 'logoutAt' => now()]);
        $token = $request->user()->token();
        $token->revoke();

        return Response::json([
            'message' => 'Token Revoked Successfully.'
        ]);
    }
}
