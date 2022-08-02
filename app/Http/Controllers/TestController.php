<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use RuntimeException;



class TestController extends Controller
{
    public function test(){

        try {
           $cars = array("Volvo", "BMW", "Toyota");
           return $cars[4];
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }
    
}
