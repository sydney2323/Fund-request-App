<?php


namespace App\Helpers;
use Request;
use App\Models\LogActivity02 as LogActivityModel;
use Auth;


class LogActivity
{


    public static function addToLog($event, $description)
    {
    	$log = [];
    	$log['event'] = $event;
    	$log['user_id'] = Auth::user()->id;
    	$log['user_email'] = Auth::user()->email;
    	$log['description'] = $description;
    	LogActivityModel::create($log);
    }


    public static function logActivityLists()
    {
    	return LogActivityModel::all();
    }


}