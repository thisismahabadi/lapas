<?php

namespace Modules\V1\Http\Controllers\Post;

use Exception;
use Illuminate\Http\Request;
use Modules\V1\Entities\Post\Post;
use App\Http\Controllers\APIController;
use Modules\V1\Http\Requests\Post\CreatePost;
use Modules\V1\Http\Requests\Post\UpdatePost;

 /**
  * @version 1.0.0
  */
class PostController extends APIController
{
    /**
     * Store a newly created post in database.
     *
     * @since 1.0.0
     *
     * @param \Modules\V1\Http\Requests\Post\CreatePost $request
     *
     * @see \Modules\V1\Http\Entities\Post\Post::store(CreatePost $request)
     *
     * @return object
     */
    public function store(CreatePost $request): object
    {
        try {
        	$post = Post::store($request);

            return $this->response('success', $post, 201);
        } catch (Exception $e) {
            return $this->response('error', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified post.
     *
     * @since 1.0.0
     *
     * @param int $id
     *
     * @see \Modules\V1\Http\Entities\Post\Post::show(int $id)
     *
     * @return object
     */
    public function show(int $id): object
    {
        try {
        	$post = Post::show($id);

            return $this->response('success', $post, 200);
        } catch (Exception $e) {
            return $this->response('error', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified post from database.
     *
     * @since 1.0.0
     *
     * @param int $id
     *
     * @see \Modules\V1\Http\Entities\Post\Post::remove(int $id)
     *
     * @return object
     */
    public function destroy(int $id): object
    {
        try {
            $post = Post::remove($id);

            return $this->response('success', $post, 204);
        } catch (Exception $e) {
            return $this->response('error', $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified post in database.
     *
     * @since 1.0.0
     *
     * @param \Modules\V1\Http\Requests\Post\UpdatePost $request
     * @param int $id
     *
     * @see \Modules\V1\Http\Entities\Post\Post::edit(UpdatePost $request, int $id)
     *
     * @return object
     */
    public function update(UpdatePost $request, int $id): object
    {
        try {
        	$post = Post::edit($request, $id);

            return $this->response('success', $post, 200);
        } catch (Exception $e) {
            return $this->response('error', $e->getMessage(), 500);
        }
    }

    /**
     * Display a listing of the post based on parameters.
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request
     *
     * @see \Modules\V1\Http\Entities\Post\Post::index(Request $request)
     *
     * @return object
     */
    public function index(Request $request): object
    {
        try {
            $posts = Post::index($request);

            return $this->response('success', $posts, 200);
        } catch (Exception $e) {
            return $this->response('error', $e->getMessage(), 500);
        }
    }
}
