<?php

namespace Modules\V1\Tests\Unit;

use Tests\TestCase;
use App\Classes\Response;
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

        $this->actingAs($user, Response::API)
             ->get('/api/v1/posts')
             ->assertJson([
                'status' => Response::SUCCESS,
                'code' => Response::HTTP_OK,
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
                'status' => Response::ERROR,
                'code' => Response::HTTP_UNAUTHORIZED,
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

        $this->actingAs($user, Response::API)
             ->json('POST', '/api/v1/posts', $data)
             ->assertJson([
                'status' => Response::SUCCESS,
                'code' => Response::HTTP_CREATED,
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

        $this->actingAs($user, Response::API)
             ->json('POST', '/api/v1/posts', $data)
             ->assertJson([
                'status' => Response::ERROR,
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
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
                'status' => Response::ERROR,
                'code' => Response::HTTP_UNAUTHORIZED,
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

        $this->actingAs($user, Response::API)
             ->get('/api/v1/posts/'.$post->id)
             ->assertJson([
                'status' => Response::SUCCESS,
                'code' => Response::HTTP_OK,
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
                'status' => Response::ERROR,
                'code' => Response::HTTP_UNAUTHORIZED,
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

        $this->actingAs($user, Response::API)
             ->json('DELETE', '/api/v1/posts/'.$post->id)
             ->assertStatus(Response::HTTP_NO_CONTENT);
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
                'status' => Response::ERROR,
                'code' => Response::HTTP_UNAUTHORIZED,
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

        $this->actingAs($user, Response::API)
             ->json('PUT', '/api/v1/posts/'.$post->id, $data)
             ->assertJson([
                'status' => Response::SUCCESS,
                'code' => Response::HTTP_OK,
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

        $this->actingAs($user, Response::API)
             ->json('PUT', '/api/v1/posts/'.$post->id, $data)
             ->assertJson([
                'status' => Response::ERROR,
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
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
                'status' => Response::ERROR,
                'code' => Response::HTTP_UNAUTHORIZED,
             ]);
    }
}
