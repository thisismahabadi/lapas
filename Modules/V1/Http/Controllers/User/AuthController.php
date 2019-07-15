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

class AuthController extends APIController
{
    public function __construct()
    {
        $this->client = Client::where('password_client', true)->orderBy('id', 'desc')->first();
    }

    public function register(RegisterUser $request)
    {
        try {
            $user = User::create($request->all());

            return parent::response('success', 'User has been registered successfully.', 201);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

    public function login(/* LoginUser */Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

        	if (Auth::attempt([
        		'email' => $request->email,
        		'password' => $request->password,
        	])) {
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

            return parent::response('error', 'Unauthorized', 401);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

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
