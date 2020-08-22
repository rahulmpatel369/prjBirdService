<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public static $HTTP_OK = 200;
    public static $HTTP_CREATED = 201;
    public static $HTTP_ACCEPTED = 202;
    public static $HTTP_NON_AUTHORITATIVE_INFORMATION = 203;

    public static $HTTP_NO_CONTENT = 204;
    public static $HTTP_RESET_CONTENT = 205;
    public static $HTTP_PARTIAL_CONTENT = 206;
    public static $HTTP_MULTI_STATUS = 207;          // RFC4918
    public static $HTTP_ALREADY_REPORTED = 208;      // RFC5842
    public static $HTTP_IM_USED = 226;               // RFC3229

    // Redirection
    public static $HTTP_MULTIPLE_CHOICES = 300;
    public static $HTTP_MOVED_PERMANENTLY = 301;
    public static $HTTP_FOUND = 302;
    public static $HTTP_SEE_OTHER = 303;

    // The resource has not been modified since the last request
    public static $HTTP_NOT_MODIFIED = 304;
    public static $HTTP_USE_PROXY = 305;
    public static $HTTP_RESERVED = 306;
    public static $HTTP_TEMPORARY_REDIRECT = 307;
    public static $HTTP_PERMANENTLY_REDIRECT = 308;  // RFC7238

    public static $HTTP_BAD_REQUEST = 400;
    public static $HTTP_UNAUTHORIZED = 401;
    public static $HTTP_PAYMENT_REQUIRED = 402;
    public static $HTTP_FORBIDDEN = 403;
    public static $HTTP_NOT_FOUND = 404;
    public static $HTTP_METHOD_NOT_ALLOWED = 405;
    public static $HTTP_NOT_ACCEPTABLE = 406;
    public static $HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
    public static $HTTP_REQUEST_TIMEOUT = 408;

    public static $HTTP_CONFLICT = 409;
    public static $HTTP_PRECONDITION_FAILED = 412;
    public static $HTTP_EXPECTATION_FAILED = 417;
    public static $HTTP_UNPROCESSABLE_ENTITY = 422;                                        // RFC4918
    public static $HTTP_INTERNL_SERVER_ERROR = 500;


    public function response($message = null,$data = array(),$httpCode = 200,$statusCode = null){
        if($statusCode == null){
            $statusCode = $httpCode;
        }
        if($data){
            return response()->json(['status'=>$statusCode, 'message'=>$message,'result'=>$data],$httpCode);
        }else{
            return response()->json(['status'=>$statusCode, 'message'=>$message],$httpCode);
        }
    }

    public function errorResponse($message = null,$error = array(),$httpCode = 400,$statusCode = null){
        if($statusCode == null){
            $statusCode = $httpCode;
        }
        if($error && count($error) > 0){
            return response()->json(['status'=>$statusCode, 'message'=>$message,'error'=>$error],$httpCode); 
        }else{
            return response()->json(['status'=>$statusCode, 'message'=>$message],$httpCode); 
        }
    }
}
