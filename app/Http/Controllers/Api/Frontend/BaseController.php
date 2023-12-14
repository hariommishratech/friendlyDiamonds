<?php


namespace App\Http\Controllers\Api\Frontend;
use App\Http\Controllers\Controller;


class BaseController extends Controller
{
    
    /**
     * returns a successful response with 200 status code.
     * @param mixed $result,
     * @param mixed $msg
     * @return json 
     */
    public function handleResponse($result, $msg)
    {
    	$res = [
            'success' => true,
            'data'    => $result,
            'message' => $msg,
        ];
        return response()->json($res, 200);
    }
    
    /**
     * returns a unsuccessful response with error code.
     * @param mixed $error
     * @param mixed $code
     * @return json 
     */
    public function handleError($error, $code)
    {
        if ($code == 422 ) {
            
            \Log::channel('stack_422')->error( '422 Error: ' , 
                [
                    'error' => $error,
                    'request' => request()->all(),
                    'auth' => !empty( auth()->user() ) ? auth()->user()->id : 'No Auth' 
                ]);
        }

    	$res = [
            'success' => false,
            'errors' => $error,
        ];

        return response()->json($res, $code);
    }
}
