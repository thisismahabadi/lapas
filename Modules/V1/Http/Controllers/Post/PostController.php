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
    public function get()
    {
        try {
            $posts = Post::get();
            
            return parent::response('success', $posts, 200);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

    public function create(CreatePost $request)
    {
        try {
        	$post = Post::create($request->all());

            return parent::response('success', $post, 201);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

    public function find(int $id)
    {
        try {
        	$post = Post::findOrFail($id);

            return parent::response('success', $post, 200);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

    public function delete(int $id)
    {
        try {
            $post = Post::findOrFail($id)->delete();

            return parent::response('success', $post, 204);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

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
