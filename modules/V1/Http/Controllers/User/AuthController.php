<?php

namespace Modules\V1\Http\Controllers\User;

use Exception;
use App\Classes\Response;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Modules\V1\Entities\User\User;
use App\Http\Controllers\APIController;
use Modules\V1\Http\Requests\User\LoginUser;
use Modules\V1\Http\Requests\User\RegisterUser;

 /**
  * @version 1.0.0
  */
class AuthController extends APIController
{
    /**
     * The client access result.
     *
     * @since 1.0.0
     *
     * @var string
     */
    private $client;

    /**
     * Construct method which call when an instance has been created.
     *
     * @since 1.0.0
     *
     */
    public function __construct()
    {
        $this->client = Client::where('password_client', true)
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * Store a newly created user in database.
     *
     * @since 1.0.0
     *
     * @param \Modules\V1\Http\Requests\User\RegisterUser $request
     *
     * @see \Modules\V1\Entities\User\User::register(RegisterUser $request)
     *
     * @return object
     */
    public function register(RegisterUser $request): object
    {
        try {
            $data = User::register($request);

            return $this->response(Response::SUCCESS, $data, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->response(Response::ERROR, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Login into existing user in database.
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request
     *
     * @see \Modules\V1\Entities\User\User::login(Request $request, Client $client)
     *
     * @return object
     */
    public function login(Request $request): object
    {
        try {
            $data = User::login($request, $this->client);

            return $this->response(Response::SUCCESS, $data, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->response(Response::ERROR, $e->getMessage(), $e->getStatusCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Exchange a refresh token for an access token when the access token has expired.
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request
     *
     * @see \Modules\V1\Entities\User\User::refresh(Request $request)
     *
     * @return object
     */
    public function refresh(Request $request): object
    {
        try {
            $data = User::refreshToken($request, $this->client);

            return $this->response(Response::SUCCESS, $data, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->response(Response::ERROR, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Logout from current user.
     *
     * @since 1.0.0
     *
     * @see \Modules\V1\Entities\User\User::logout()
     *
     * @return object
     */
    public function logout(): object
    {
        try {
            $data = User::logout();

            return $this->response(Response::SUCCESS, $data, Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return $this->response(Response::ERROR, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
