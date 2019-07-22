<?php

namespace Modules\V1\Entities\Post;

use Modules\V1\Entities\Post\Post;
use Illuminate\Database\Eloquent\Model;

class PostAction extends Model
{
	/**
	 * The query result.
	 *
	 * @var string
	 */
    public $query;

	/**
	 * The result page limit.
	 *
	 * @var int
	 */
    CONST pageLimit = 10;

    /**
     * Initilize action class to apply queries in the listing of the post.
     *
     * @return \Modules\V1\Entities\Post\Action
     */
    public function init()
    {
        $this->query = new Post;

    	return $this;
    }

    /**
     * Search in the listing of the post.
     *
     * @param string $data
     * @return \Modules\V1\Entities\Post\Action
     */
    public function search(string $data = null)
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
     * @param string $fieldName
     * @param string $sortType
     * @return \Modules\V1\Entities\Post\Action
     */
    public function sort(string $fieldName = null, string $sortType = null)
    {
    	if ($fieldName && $sortType) {
	        if (in_array($fieldName, Post::$sortArray)) {
	        	$this->query = $this->query->orderBy($fieldName, $sortType);
			}
		}

        return $this;
    }

    /**
     * Filter the listing of the post.
     *
     * @param string $columnName
     * @return \Modules\V1\Entities\Post\Action
     */
    public function filter(string $columnName = null)
    {
    	if ($columnName) {
	        if (in_array($columnName, Post::$sortArray)) {
	    		$this->query = $this->query->select($columnName);
	    	}
    	}

    	return $this;
    }

    /**
     * Paginate the listing of the post.
     *
     * @param int $pageNumber
     * @param int $pageLimit
     * @return \Modules\V1\Entities\Post\Action
     */
    public function paginate(int $pageNumber = null, int $pageLimit = self::pageLimit)
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
     * @return \Modules\V1\Entities\Post\Action
     */
    public function execute()
    {
        return $this->query->get();
    }
}
