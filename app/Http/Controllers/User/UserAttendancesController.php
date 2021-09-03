<?php

namespace App\Http\Controllers\User;

use Orion\Http\Controllers\RelationController;
use Orion\Concerns\DisableAuthorization;
use App\Models\User;

class UserAttendancesController extends RelationController
{
    use DisableAuthorization;
    protected $model = User::class;
    protected $relation = 'attendanceEntries';
}
