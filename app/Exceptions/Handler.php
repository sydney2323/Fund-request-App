<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // $this->reportable(function (Throwable $e) {
        //     //
        // });
        $this->renderable(function (Throwable $e) {
            
                return response(['error' => $e->getMessage() ], $e->getCode() ?: 500);
           
        });
    }
    

    // public function render(){
    //     if ($exception instanceof MissingScopeException && $request->wantsJson()){
    //         return response()->json([
    //             'error' => 'forbidden',
    //         ], 403);
    //     }
    // }  mysql://ba1bba87c885dc:88718f26@us-cdbr-east-06.cleardb.net/heroku_804074f8c90c85a?reconnect=true
}
