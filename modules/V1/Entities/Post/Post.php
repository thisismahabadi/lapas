<?php

namespace Modules\V1\Entities\Post;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Modules\V1\Entities\Post\PostAction;
use Modules\V1\Http\Requests\Post\CreatePost;
use Modules\V1\Http\Requests\Post\UpdatePost;

 /**
  * @version 1.0.0
  */
class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'title', 'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * Search in the listing of the post.
     *
     * @since 1.0.0
     *
     * @param string $data
     *
     * @return object
     */
    public static function search(string $data): object
    {
        $data = self::where('title', 'like', '%'.$data.'%')
            ->orWhere('description', 'like', '%'.$data.'%');

        return $data;
    }

    /**
     * Store a newly created post in database.
     *
     * @since 1.0.0
     *
     * @param \Modules\V1\Http\Requests\Post\CreatePost $request
     *
     * @return object
     */
    public static function store(CreatePost $request): object
    {
        $post = self::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return $post;
    }

    /**
     * Display the specified post.
     *
     * @since 1.0.0
     *
     * @param int $id
     *
     * @return object
     */
    public static function show(int $id): object
    {
        $post = self::findOrFail($id);

        return $post;
    }

    /**
     * Remove the specified post from database.
     *
     * @since 1.0.0
     *
     * @param int $id
     *
     * @return bool
     */
    public static function remove(int $id): bool
    {
        $post = self::findOrFail($id)
            ->delete();

        return $post;
    }

    /**
     * Update the specified post in database.
     *
     * @since 1.0.0
     *
     * @param \Modules\V1\Http\Requests\Post\UpdatePost $request
     * @param int $id
     *
     * @return bool
     */
    public static function edit(UpdatePost $request, int $id): bool
    {
        $post = self::where('id', $id)->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return $post;
    }

    /**
     * Display a listing of the post based on parameters.
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return object
     */
    public static function index(Request $request): object
    {
        $posts = (new PostAction)->init()
            ->search($request->search)
            ->sort($request->field, $request->value)
            ->filter($request->filter)
            ->paginate($request->page)
            ->execute();

        return $posts;
    }
}
