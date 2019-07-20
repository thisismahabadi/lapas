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
        if (in_array($fieldName, self::$sortArray)) {
            return self::orderBy($fieldName, $sortType);
        }
    }

    /**
     * Paginate a listing of the post.
     */
    public static function paginate(array $arguments)
    {
        //debug
        $query = array_slice($arguments['query'] ?? self::get()->toArray(), $arguments['pageLimit']*$arguments['pageNumber']-$arguments['pageLimit'], $arguments['pageLimit']);
        $paginator = new Paginator($query, /* query count debug */self::count(), $arguments['pageLimit'], $arguments['pageNumber'], [
            'path'  => request()->url(),
            'query' => request()->query(),
        ]);

        return $paginator;
    }

    /**
     * Search in the listing of the post.
     */
    public static function search(string $data)
    {
        return self::where('title', 'like', '%'.$data.'%')
            ->orWhere('description', 'like', '%'.$data.'%');
    }

    /**
     * Action
     */

    public $query;

    public function actionInit()
    {
        $this->query = new $this;

        return $this;
    }

    public function actionSearch(string $data)
    {
        $searchedPosts = $this->search($data)->pluck('id');

        if ($searchedPosts)
            $this->query = $this->query->whereIn('id', $searchedPosts);

        return $this;
    }

    // public function actionSort(string $fieldName, string $sortType)
    // {
    //     // $sortedPosts = self::sort($fieldName, $sortType);

    //     self::$query = self::$query->sort($fieldName, $sortType);

    //     return self;
    // }

    // public function paginate()
    // {
    //     $this->query = self::paginate();

    //     return $this;
    // }
}
