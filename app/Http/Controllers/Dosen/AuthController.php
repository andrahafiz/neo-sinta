<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Mahasiswa;
use App\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Dosen\LoginRequest;
use App\Http\Requests\Mahasiswa\RegisterRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        config(['auth.guards.dosen-guard.driver' => 'session']);
        if (Auth::guard('dosen-guard')->attempt(['nip' => $request->nip, 'password' => $request->password], $request->get('remember'))) {
            //generate the token for the user
            $token = auth()->guard('dosen-guard')->user()
                ->createToken('authToken')->accessToken;

            return Response::json([
                'user' => auth()->guard('dosen-guard')->user(),
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
        Log::info('LOGOUT USER', ['data' => auth()->guard('dosen-guard')->user(), 'logoutAt' => now()]);
        $token = $request->user()->token();
        $token->revoke();

        return Response::json([
            'message' => 'Token Revoked Successfully.'
        ]);
    }
}
