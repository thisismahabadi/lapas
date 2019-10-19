<?php

namespace Modules\V1\Tests\Unit;

use Tests\TestCase;
use Modules\V1\Entities\User\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @todo Add backend error test and registration and login validation method
 */
class UserTest extends TestCase
{
    /**
     * Register user successfully.
     *
     * @return void
     */
    public function testSuccessfullUserRegisteration()
    {
        $user = factory(User::class)->make();

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
        ];

        $this->json('POST', '/api/v1/register', $data)
             ->assertJson([
                'status' => 'success',
                'code' => 201,
             ]);
    }

    /**
     * Register user with duplicate email to get error.
     *
     * @return void
     */
    public function testUserRegisterationWithDuplicateError()
    {
        $user = factory(User::class)->create();

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
        ];

        $this->json('POST', '/api/v1/register', $data)
             ->assertJson([
                'status' => 'error',
                'code' => 422,
             ]);
    }

    /**
     * Login user successfully.
     *
     * @return void
     */
    public function testSuccessfullUserLogin()
    {
        $user = factory(User::class)->states('unhash')->make();
        $password = $user->password;
        $user = factory(User::class)->create([
                    'password' => Hash::make($password),
                ]);

        $data = [
            'email' => $user->email,
            'password' => $password,
        ];

        $this->json('POST', '/api/v1/login', $data)
             ->assertJson([
                'status' => 'success',
                'code' => 200,
             ]);
    }

    /**
     * Login user with wrong enteries to get error.
     *
     * @return void
     */
    public function testUserLoginWithWrongEnteries()
    {
        $user = factory(User::class)->make();

        $data = [
            'email' => $user->email,
            'password' => $user->password,
        ];

        $this->json('POST', '/api/v1/login', $data)
             ->assertJson([
                'status' => 'error',
                'code' => 401,
             ]);
    }
}
