<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $startTime = Carbon::createFromFormat('H:i a', '08:00 AM');
        $endTime = Carbon::createFromFormat('H:i a', '07:00 PM');
        $currentTime = Carbon::now();

        if($currentTime->between($startTime, $endTime, true)){
            dd('In Between');
        }else{
            dd('In Not Between');
        }
    }
    //protected $current = Carbon::now();
    //public $today = Carbon::today();

    //public $startDate = Carbon::createFromFormat('Y-m-d','2019-10-01');
    //public $endDate = Carbon::createFromFormat('Y-m-d','2019-10-30');

    //public $check = Carbon::now()->between($startDate,$endDate);

}
