<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function successResponse($result, $message, $code)
    {
    	$response = [
            'success' => true,
            'message' => $message,
            'data'    => $result,
        ];

        return response()->json($response, $code);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function errorResponse($message, $code)
    {
    	$response = [
            'success'   => false,
            'message'   => $message,
            'data'      => null
        ];

        return response()->json($response, $code);
    }
}