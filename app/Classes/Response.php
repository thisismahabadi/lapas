<?php

namespace App\Classes;

use Illuminate\Http\Response as HTTPResponse;

 /**
  * @version 1.0.0
  */
class Response extends HTTPResponse
{
    const API = "api";
    const ERROR = "Error";
    const SUCCESS = "Success";
    const UNAUTHORIZED = "Unauthorized";
    const UNAUTHENTICATED = "Unauthenticated.";
}