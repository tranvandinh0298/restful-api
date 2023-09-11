<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e) {
            Log::debug('exception thrown instance of:' . get_class($e));
        });

        // validation exception
        $this->renderable(function (ValidationException $e) {
            Log::debug('ValidationException');
            return $this->errorResponse($e->getMessage(), 400);
        });

        // not found exception
        $this->renderable(function (NotFoundHttpException $e) {

            if ($e->getPrevious() && $e->getPrevious() instanceof ModelNotFoundException) {
                Log::debug('ModelNotFoundException');
                return $this->errorResponse($e->getMessage(), 404);
            }

            Log::debug('NotFoundHttpException');
            return $this->errorResponse($e->getMessage(), $e->getStatusCode());
        });

        // unauthenticated

        // unauthorized
        $this->renderable(function (UnauthorizedHttpException $e) {
            Log::debug('UnauthorizedHttpException');
            return $this->errorResponse($e->getMessage(), 404);
        });

        // method not allowed
        $this->renderable(function (MethodNotAllowedHttpException $e) {
            Log::debug('MethodNotAllowedHttpException');
            return $this->errorResponse($e->getMessage(), 405);
        });

        // Throttle exception : too many request attempts
        $this->renderable(function (ThrottleRequestsException $e) {
            Log::debug('Throttle exception');
            return $this->errorResponse($e->getMessage(), 429);
        });

        // http 
        $this->renderable(function (HttpException $e) {
            Log::debug('HttpException');
            return $this->errorResponse($e->getMessage(), $e->getStatusCode());
        });

        // Database
        $this->renderable(function (QueryException $e) {
            Log::debug('QueryException');
            $errorCode = $e->errorInfo[1];

            if ($errorCode == 1451) {
                return $this->errorResponse('Cannot remove this resource permanently. It is related with any other resource', 409);
            }
            return $this->errorResponse($e->getMessage(), 500);
        });

        // unknow exceptions
        $this->renderable(function (Exception $e) {
            Log::debug('UnknowException');
            return $this->errorResponse($e->getMessage(), 500);
        });
    }
}
