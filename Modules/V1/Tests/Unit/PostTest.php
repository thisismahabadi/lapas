<?php

namespace Modules\V1\Tests\Unit;

use Tests\TestCase;
use Modules\V1\Entities\User\User;
use Modules\V1\Entities\Post\Post;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @todo Add backend error test
 */
class PostTest extends TestCase
{
    /**
     * Visit posts page as logged-in user successfully.
     *
     * @return void
     */
    public function testVisitPostsAsLoggedInUser()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
             ->get('/api/v1/posts')
             ->assertJson([
                'status' => 'success',
                'code' => 200,
             ]);
    }

    /**
     * Visit posts page as guest successfully.
     *
     * @return void
     */
    public function testVisitPostsAsGuest()
    {
        $this->get('/api/v1/posts')
             ->assertJson([
                'status' => 'error',
                'code' => 401,
             ]);
    }

    /**
     * Create posts successfully.
     *
     * @return void
     */
    public function testCreatePostSuccessfully()
    {
        $post = factory(Post::class)->make();
        $user = factory(User::class)->create();

        $data = [
            'title' => $post->title,
            'description' => $post->description,
        ];

        $this->actingAs($user)
             ->json('POST', '/api/v1/posts', $data)
             ->assertJson([
                'status' => 'success',
                'code' => 201,
             ]);
    }

    /**
     * Create posts with error.
     *
     * @return void
     */
    public function testCreatePostWithError()
    {
        $post = factory(Post::class)->make();
        $user = factory(User::class)->create();

        $data = [
            'title' => $post->title,
        ];

        $this->actingAs($user)
             ->json('POST', '/api/v1/posts', $data)
             ->assertJson([
                'status' => 'error',
                'code' => 422,
             ]);
    }

    /**
     * Create posts as guest.
     *
     * @return void
     */
    public function testCreatePostAsGuest()
    {
        $post = factory(Post::class)->make();

        $data = [
            'title' => $post->title,
            'description' => $post->description,
        ];

        $this->json('POST', '/api/v1/posts', $data)
             ->assertJson([
                'status' => 'error',
                'code' => 401,
             ]);
    }

    /**
     * Visit specific post.
     *
     * @return void
     */
    public function testVisitSpecificPost()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $this->actingAs($user)
             ->get('/api/v1/posts/'.$post->id)
             ->assertJson([
                'status' => 'success',
                'code' => 200,
             ]);
    }

    /**
     * Visit specific post as guest.
     *
     * @return void
     */
    public function testVisitSpecificPostAsGuest()
    {
        $post = factory(Post::class)->create();

        $this->get('/api/v1/posts/'.$post->id)
             ->assertJson([
                'status' => 'error',
                'code' => 401,
             ]);
    }

    /**
     * Delete specific post.
     *
     * @return void
     */
    public function testDeleteSpecificPost()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $this->actingAs($user)
             ->json('DELETE', '/api/v1/posts/'.$post->id)
             ->assertResponseStatus(204);
    }

    /**
     * Delete specific post as guest.
     *
     * @return void
     */
    public function testDeleteSpecificPostAsGuest()
    {
        $post = factory(Post::class)->create();

        $this->json('DELETE', '/api/v1/posts/'.$post->id)
             ->assertJson([
                'status' => 'error',
                'code' => 401,
             ]);
    }

    /**
     * Update specific post successfully.
     *
     * @return void
     */
    public function testUpdateSpecificPostSuccessfully()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $data = [
            'title' => $post->title,
            'description' => $post->description,
        ];

        $this->actingAs($user)
             ->json('PUT', '/api/v1/posts/'.$post->id, $data)
             ->assertJson([
                'status' => 'success',
                'code' => 200,
             ]);
    }

    /**
     * Update specific post with error.
     *
     * @return void
     */
    public function testUpdateSpecificPostWithError()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $data = [
            'title' => $post->title,
        ];

        $this->actingAs($user)
             ->json('PUT', '/api/v1/posts/'.$post->id, $data)
             ->assertJson([
                'status' => 'error',
                'code' => 422,
             ]);
    }

    /**
     * Update specific post as guest.
     *
     * @return void
     */
    public function testUpdateSpecificPostAsGuest()
    {
        $post = factory(Post::class)->create();

        $data = [
            'title' => $post->title,
            'description' => $post->description,
        ];

        $this->json('PUT', '/api/v1/posts/'.$post->id, $data)
             ->assertJson([
                'status' => 'error',
                'code' => 401,
             ]);
    }
}
