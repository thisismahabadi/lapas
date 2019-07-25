<?php

namespace Modules\V1\Entities\Post;

use Modules\V1\Entities\Post\Post;
use Illuminate\Database\Eloquent\Model;

 /**
  * @version 1.0.0
  */
class PostAction extends Model
{
	/**
	 * The query result.
     *
     * @since 1.0.0
	 *
	 * @var string
	 */
    public $query;

	/**
	 * The result page limit constant.
     *
     * @since 1.0.0
	 *
	 * @var int
	 */
    CONST PAGELIMIT = 10;

    /**
     * Initilize action class to apply queries in the listing of the post.
     *
     * @since 1.0.0
     *
     * @return \Modules\V1\Entities\Post\PostAction
     */
    public function init()
    {
        $this->query = new Post;

    	return $this;
    }

    /**
     * Search in the listing of the post.
     *
     * @since 1.0.0
     *
     * @see \Modules\V1\Entities\Post\Post::search(string $data) for search in the listing of the post
     *
     * @param string $data
     *
     * @return \Modules\V1\Entities\Post\PostAction
     */
    public function search(?string $data = null)
    {
    	if ($data) {
        	$searchedPosts = Post::search($data)->pluck('id');

	        if ($searchedPosts) {
	            $this->query = $this->query->whereIn('id', $searchedPosts);
	        }
    	}

        return $this;
    }

    /**
     * Sort the listing of the post.
     *
     * @since 1.0.0
     *
     * @param string $fieldName
     * @param string $sortType
     *
     * @return \Modules\V1\Entities\Post\PostAction
     */
    public function sort(?string $fieldName = null, ?string $sortType = null)
    {
    	if ($fieldName && $sortType) {
        	$this->query = $this->query->orderBy($fieldName, $sortType);
		}

        return $this;
    }

    /**
     * Filter the listing of the post.
     *
     * @since 1.0.0
     *
     * @param string $columnName
     *
     * @return \Modules\V1\Entities\Post\PostAction
     */
    public function filter(?string $columnName = null)
    {
    	if ($columnName) {
    		$this->query = $this->query->select($columnName);
    	}

    	return $this;
    }

    /**
     * Paginate the listing of the post.
     *
     * @since 1.0.0
     *
     * @todo Add more details for paginate
     *
     * @param int $pageNumber
     * @param int $pageLimit
     *
     * @return \Modules\V1\Entities\Post\PostAction
     */
    public function paginate(?int $pageNumber = null, int $pageLimit = self::PAGELIMIT)
    {
        if ($pageNumber && $pageLimit) {
            $offset = ($pageNumber - 1) * $pageLimit;
            $this->query = $this->query->skip($offset)->take($pageLimit);
        }

        return $this;
    }

    /**
     * Execute the result of queries in the listing of the post.
     *
     * @since 1.0.0
     *
     * @return \Modules\V1\Entities\Post\PostAction
     */
    public function execute()
    {
        return $this->query->get();
    }
}
