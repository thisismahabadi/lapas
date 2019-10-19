<?php

namespace App\Exceptions;

use Exception;
use App\Classes\Response;
use App\Http\Controllers\APIController;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $errorMessage = $exception->getMessage();

        if ($this->isHttpException($exception)) {
            $errorCode = $exception->getStatusCode();

            if ($errorCode == Response::HTTP_UNAUTHORIZED) {
                return (new APIController)->response(Response::ERROR, Response::UNAUTHORIZED, Response::HTTP_UNAUTHORIZED);
            }

            if ($errorCode === Response::HTTP_METHOD_NOT_ALLOWED) {
                return (new APIController)->response(Response::ERROR, $errorMessage, Response::HTTP_METHOD_NOT_ALLOWED);
            }
        }

        if ($exception instanceof ThrottleRequestsException) {
            return (new APIController)->response(Response::ERROR, $errorMessage, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($exception instanceof ValidationException) {
            return (new APIController)->response(Response::ERROR, $exception->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($exception instanceof AuthenticationException) {
            return (new APIController)->response(Response::ERROR, Response::UNAUTHORIZED, Response::HTTP_UNAUTHORIZED);
        }

        return (new APIController)->response(Response::ERROR, $errorMessage, Response::HTTP_INTERNAL_SERVER_ERROR);

        return parent::render($request, $exception);
    }
}
