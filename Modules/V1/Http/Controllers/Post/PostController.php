<?php

namespace Modules\V1\Http\Controllers\Post;

use Exception;
use Illuminate\Http\Request;
use Modules\V1\Entities\Post\Post;
use Modules\V1\Entities\Post\Action;
use App\Http\Controllers\APIController;
use Modules\V1\Http\Requests\Post\CreatePost;
use Modules\V1\Http\Requests\Post\UpdatePost;

use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class PostController extends APIController
{
    /**
     * Store a newly created post in database.
     *
     * @param  \Modules\V1\Http\Requests\Post\CreatePost  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePost $request)
    {
        try {
        	$post = Post::create([
                'title' => $request->title,
                'description' => $request->description,
            ]);

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
        	$post = Post::where('id', $id)->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return parent::response('success', $post, 200);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

    /**
     * Display a listing of the post based on parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $search = $request->search ?? null;
            $field = $request->field ?? null;
            $value = $request->value ?? null;
            $filter = $request->filter ?? null;

            $result = (new Action)->init()
                ->search($search)
                ->sort($field, $value)
                ->filter($filter)
                ->execute();
                //->paginate();

            return parent::response('success', $result, 200);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

    /**
     * Paginate a listing of the post.
     */
    public function paginate(array $parameters)
    {
        try {
            $posts = Post::paginate($parameters);

            return parent::response('success', $posts, 200);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }
}
