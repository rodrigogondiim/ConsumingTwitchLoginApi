<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Encryption\MissingAppKeyException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Bootstrap\HandleExceptions;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof NotFoundHttpException or $e instanceof MissingAppKeyException) {
            return response()->json([
                'code' => Response::HTTP_NOT_FOUND,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof ValidationException) {
            return response()->json([
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => $e->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
 
        if ($e instanceof AuthorizationException) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN,
                'message' => $e->getMessage()
            ], Response::HTTP_FORBIDDEN);
        }

        if ($e instanceof HandleExceptions) {
            return response()->json([
                'code' => Response::HTTP_UNAUTHORIZED,
                'message' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }
  
        if ($e instanceof AccessDeniedHttpException) {
            return response()->json([
                'code' => Response::HTTP_UNAUTHORIZED,
                'message' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($e instanceof Throwable) {
            return response()->json([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Internal Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
