<?php

namespace Modules\V1\Entities\User;

use DB;
use Auth;
use Exception;
use App\Classes\Response;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Modules\V1\Http\Requests\User\RegisterUser;
use Illuminate\Foundation\Auth\User as Authenticatable;

 /**
  * @version 1.0.0
  */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Store a newly created user in database.
     *
     * @since 1.0.0
     *
     * @param \Modules\V1\Http\Requests\User\RegisterUser $request
     *
     * @return bool
     */
    public static function register(RegisterUser $request): bool
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            return true;
        }

        return false;
    }

    /**
     * Login into existing user in database.
     *
     * @since 1.0.0
     *
     * @todo Improve better way for login and Add validation
     *
     * @param \Illuminate\Http\Request $request
     * @param \Laravel\Passport\Client $client
     *
     * @return array
     */
    public static function login(Request $request, Client $client): array
    {
        // $validator = $this->validate($request, [
        //     'email' => 'required|email',
        //     'password' => 'required|string',
        // ]);

        $user = self::where('email', $request->email)
            ->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $params = [
                    'grant_type' => 'password',
                    'client_id' => $client->id,
                    'client_secret' => $client->secret,
                    'username' => $request->email,
                    'password' => $request->password,
                    'scope' => '*'
                ];
                $request->request->add($params);
                $proxy = Request::create('oauth/token', 'POST');
                $oauth = Route::dispatch($proxy);

                $data = json_decode($oauth->getContent(), true);

                return $data;
            }
        }

        throw new Exception(Response::UNAUTHORIZED, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Exchange a refresh token for an access token when the access token has expired.
     *
     * @since 1.0.0
     *
     * @todo Improve better way for requesting refresh token
     *
     * @param \Illuminate\Http\Request $request
     * @param \Laravel\Passport\Client $client
     *
     * @return array
     */
    public static function refreshToken(Request $request, Client $client): array
    {
        // $this->validate($request, [
        //     'refresh_token' => 'required',
        // ]);

        $params = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refreshToken,
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'scope' => ''
        ];
        $request->request->add($params);
        $proxy = Request::create('oauth/token', 'POST');
        $oauth = Route::dispatch($proxy);

        $data = json_decode($oauth->getContent(), true);

        return $data;
    }

    /**
     * Logout from current user.
     *
     * @since 1.0.0
     *
     * @todo Improve better way for logout
     *
     * @return bool
     */
    public static function logout(): bool
    {
        $accessToken = Auth::user()
            ->token();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update(['revoked' => true]);

        $accessToken->revoke();

        return true;
    }
}
