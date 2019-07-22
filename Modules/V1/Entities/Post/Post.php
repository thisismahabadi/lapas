<?php

namespace Modules\V1\Entities\Post;

use Illuminate\Database\Eloquent\Model;

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
     * @param string $data
     *
     * @return \Modules\V1\Entities\Post\Post
     */
    public static function search(string $data)
    {
        return self::where('title', 'like', '%'.$data.'%')
            ->orWhere('description', 'like', '%'.$data.'%');
    }
}
