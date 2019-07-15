<?php

namespace Modules\V1\Http\Controllers\Post;

use Exception;
use Illuminate\Http\Request;
use Modules\V1\Entities\Post\Post;
use App\Http\Controllers\APIController;
use Modules\V1\Http\Requests\Post\CreatePost;
use Modules\V1\Http\Requests\Post\UpdatePost;

class PostController extends APIController
{
    /**
     * Display a listing of the post.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $posts = Post::get();
            
            return parent::response('success', $posts, 200);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created post in database.
     *
     * @param  \Modules\V1\Http\Requests\Post\CreatePost  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePost $request)
    {
        try {
        	$post = Post::create($request->all());

            return parent::response('success', $post, 201);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified post.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        try {
        	$post = Post::findOrFail($id);

            return parent::response('success', $post, 200);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified post from database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $post = Post::findOrFail($id)->delete();

            return parent::response('success', $post, 204);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified post in database.
     *
     * @param  \Modules\V1\Http\Requests\Post\UpdatePost  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdatePost $request,
        int $id
    ) {
        try {
        	$post = Post::where('id', $id)->update($request->all());

            return parent::response('success', $post, 200);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }
}
