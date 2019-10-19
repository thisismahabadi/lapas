<?php

namespace App\Http\Middleware;

use App\Classes\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return abort(Response::HTTP_UNAUTHORIZED, Response::UNAUTHORIZED);
            // return route('unauthorized');
        }
    }
}
