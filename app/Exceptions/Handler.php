<?php

namespace App\Exceptions;

use Throwable;
use App\Contracts\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use League\OAuth2\Server\Exception\OAuthServerException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->wantsJson())
                return Response::abortNotFound();
        });

        $this->renderable(function (ValidationException $e, Request $request) {
            if ($request->wantsJson())
                return Response::abortFormInvalid($e);
        });

        $this->renderable(function (OAuthServerException $e, $request) {
            if ($request->wantsJson())
                return Response::abortUnauthorized($e);
        });

        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return Response::abortForbidden($e);
            // return response()->json([
            //     'responseMessage' => 'You do not have the required authorization.',
            //     'responseStatus'  => 403,
            // ]);
        });

        // if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
        //     return response()->json([
        //         'responseMessage' => 'You do not have the required authorization.',
        //         'responseStatus'  => 403,
        //     ]);
        // }
    }
}
