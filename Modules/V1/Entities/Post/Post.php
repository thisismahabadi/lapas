<?php

namespace Modules\V1\Entities\Post;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

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
     * The attributes that are available to sort by.
     *
     * @var array
     */
    public static $sortArray = [
        'id', 'title', 'description', 'created_at', 'updated_at',
    ];

    /**
     * Sort a listing of the post.
     */
    public static function sort(string $fieldName, string $sortType)
    {
        return self::orderBy($fieldName, $sortType);
    }

    /**
     * Paginate a listing of the post.
     */
    public static function paginate($query, $pageLimit, $pageNumber)
    {
        $paginator = new Paginator($query->get(), self::count(), $pageLimit = 10, $pageNumber = 1, [
            'path'  => request()->url(),
            'query' => request()->query(),
        ]);

        return $paginator;
    }
}
