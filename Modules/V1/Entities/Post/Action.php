<?php

namespace Modules\V1\Entities\Post;

use Modules\V1\Entities\Post\Post;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    /**
     * Actions for searching and sorting and paginating and filtering
     */

    public $query;

    public function init()
    {
        $this->query = Post::where('id', '!=', '1000000');

    	return $this;
    }

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

    public function sort(string $fieldName = null, string $sortType = null)
    {
    	if ($fieldName && $sortType) {
	        if (in_array($fieldName, Post::$sortArray)) {
	        	$this->query = $this->query->orderBy($fieldName, $sortType);
			}
		}

        return $this;
    }

    public function filter(string $columnName = null)
    {
    	if ($columnName) {
	        if (in_array($columnName, Post::$sortArray)) {
	    		$this->query = $this->query->select($columnName);
	    	}
    	}

    	return $this;
    }

    public function execute()
    {
        return $this->query->get();
    }

    // public function paginate()
    // {
    //     $this->query = self::paginate();

    //     return $this;
    // }
}
