<?php

namespace Modules\V1\Http\Controllers\Post;

use Exception;
use Illuminate\Http\Request;
use Modules\V1\Entities\Post\Post;
use App\Http\Controllers\APIController;
use Modules\V1\Http\Requests\Post\CreatePost;
use Modules\V1\Http\Requests\Post\UpdatePost;

use Illuminate\Pagination\LengthAwarePaginator as Paginator;

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

    public $query;

    /**
     * Display a listing of the post based on parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get1(Request $request)
    {
        $result = (new Post)->actionInit()
            ->actionSearch($request->search);
            //->actionSort($request->field, $request->value);
            //->paginate();

        return $result;
        die;

        try {

            // if ($request->has('page')) {
            //     if ($posts ?? null) {
            //         return $this->paginate([
            //             'query' => $posts->get()->toArray(),
            //             'pageLimit' => 10,
            //             'pageNumber' => $request->page,
            //         ]);
            //     }

            //     return $this->paginate([
            //         'pageLimit' => 10,
            //         'pageNumber' => $request->page,
            //     ]);
            // }

            if (!$request->has('page') && !$request->has('field') && !$request->has('value') && !$request->has('search')) {
                return $this->index();
            }

            // return parent::response('success', $this->query->get(), 200);
        } catch (Exception $e) {
            return 'error';
            // return parent::response('error', $e->getMessage(), 500);
        }
    }

    /**
     * Sort a listing of the post.
     */
    public function sort(string $fieldName, string $sortType)
    {
        try {
            if ($fieldName && $sortType) {
                return Post::sort($fieldName, $sortType)->pluck('id');
            }
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

    /**
     * Search in the listing of the post.
     */
    public static function search(string $data)
    {
        try {
            if ($data) {
                return Post::search($data)->pluck('id');
            }
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }
}
