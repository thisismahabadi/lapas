<?php

namespace App\Classes;

use Illuminate\Http\Response as HTTPResponse;

 /**
  * @version 1.0.0
  */
class Response extends HTTPResponse
{
    const ERROR = "Error";
    const SUCCESS = "Success";
    const UNAUTHORIZED = "Unauthorized";
}