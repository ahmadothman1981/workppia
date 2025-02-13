<?php
namespace App\Controllers;

class ErrorController
{
    /**
     * 404 not founr error
     * return void
     */
    public static function  notFound($message = 'Page Not Found')
    {
        http_response_code(404);
        loadView('error',[
            'status' => '404' ,
            'message' => $message
        ]);
    }
    /**
     * 403 unauthorized error
     * return void
     */
    public static function  unauthorized($message = 'You are not Authorized')
    {
        http_response_code(404);
        loadView('error',[
            'status' => '403' ,
            'message' => $message
        ]);
    }
}