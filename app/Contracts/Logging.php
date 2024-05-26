<?php

namespace App\Contracts;

use Exception;
use JsonSerializable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;
use League\OAuth2\Server\Exception\OAuthServerException;


abstract class Logging
{

    public static function log(string $description, $contex)
    {
        $user = Auth()->user();
        Log::withContext(['user' => [
            "id"    => $user->id,
            "name"  => $user->name,
            "username"  => $user->username,
            "roles"     => $user->roles,
        ]]);
        Log::info($description, ["data" => $contex]);
    }
}
