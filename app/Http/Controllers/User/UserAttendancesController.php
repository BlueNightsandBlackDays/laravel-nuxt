<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Orion\Http\Controllers\RelationController;

class UserAttendancesController extends RelationController
{
    protected $model = User::class;
    protected $relation = 'attendanceEntries';
}
