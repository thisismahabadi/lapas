<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

 /**
  * @version 1.0.0
  */
class APIController extends Controller
{
    /**
     * Custom method to return response.
     *
     * @since 1.0.0
     *
     * @param string $status
     * @param mixed $data
     * @param int $code
     *
     * @return \Illuminate\Http\Response
     */
    public function response(string $status, $data, int $code)
    {
    	return response()->json([
    		'status' => $status,
    		'data' => $data,
    		'code' => $code,
    	], $code);
    }

    public function unauthorized()
    {
        return $this->response('error', 'Unauthorized', 401);
    }
}
