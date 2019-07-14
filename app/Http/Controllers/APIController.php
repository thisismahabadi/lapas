<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIController extends Controller
{
    public function response(string $status, $data, int $code)
    {
    	return response()->json([
    		'status' => $status,
    		'data' => $data,
    		'code' => $code,
    	], $code);
    }
}
