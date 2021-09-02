<?php

namespace App\Http\Controllers\Attendance;

use Orion\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Orion\Http\Requests\Request;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = Attendance::class;
    protected $resource = AttendanceResource::class;

    protected function buildIndexFetchQuery(Request $request, array $requestedRelations): Builder
    {
        $query = parent::buildIndexFetchQuery($request, $requestedRelations);

        $query->join('users', 'users.id', '=', 'attendances.user_id')
            ->select('attendances.*', 'users.first_name', 'users.middle_name')
            ->orderByDesc('created_at')
            ->simplePaginate(10);

        return $query;
    }
}
