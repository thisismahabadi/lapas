<?php

namespace Modules\V1\Http\Controllers\User;

use DB;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Modules\V1\Entities\User\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
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
        $this->client = Client::where('password_client', true)->orderBy('id', 'desc')->first();
    }

    /**
     * Store a newly created user in database.
     *
     * @since 1.0.0
     *
     * @param \Modules\V1\Http\Requests\User\RegisterUser $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterUser $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return parent::response('success', 'User has been registered successfully.', 201);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

    /**
     * Login into existing user in database.
     *
     * @since 1.0.0
     *
     * @todo Improve better way for login and Add validation
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(/* LoginUser */Request $request)
    {
        try {
            $validator = $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);
            $user = User::where('email', $request->email)->first();

            if ($user) {
            	if (Hash::check($request->password, $user->password)) {
                    $params = [
                        'grant_type' => 'password',
                        'client_id' => $this->client->id,
                        'client_secret' => $this->client->secret,
                        'username' => $request->email,
                        'password' => $request->password,
                        'scope' => '*'
                    ];
                    $request->request->add($params);
                    $proxy = Request::create('oauth/token', 'POST');
                    $oauth = Route::dispatch($proxy);

                    return parent::response('success', json_decode($oauth->getContent(), true), 200);
                }
            }

            return parent::response('error', 'Unauthorized', 401);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

    /**
     * Exchange a refresh token for an access token when the access token has expired.
     *
     * @since 1.0.0
     *
     * @todo Improve better way for requesting refresh token
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh(Request $request)
    {
        try {
            $this->validate($request, [
                'refresh_token' => 'required',
            ]);

            $params = [
                'grant_type' => 'refresh_token',
                'refresh_token' => $request->refresh_token,
                'client_id' => $this->client->id,
                'client_secret' => $this->client->secret,
                'scope' => ''
            ];
            $request->request->add($params);
            $proxy = Request::create('oauth/token', 'POST');
            $oauth = Route::dispatch($proxy);

            return parent::response('success', json_decode($oauth->getContent(), true), 200);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

    /**
     * Logout from current user.
     *
     * @since 1.0.0
     *
     * @todo Improve better way for logout
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        try {
            $accessToken = Auth::user()->token();
            DB::table('oauth_refresh_tokens')->where('access_token_id', $accessToken->id)->update(['revoked' => true]);
            $accessToken->revoke();

            return parent::response('error', 'User has been logout successfully.', 204);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }
}
