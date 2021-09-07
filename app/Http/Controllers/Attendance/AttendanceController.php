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

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy() : array
    {
        return ['user.first_name', 'user.middle_name'];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy() : array
    {
        return ['id', 'first_name', 'middle_name', 'options->visible'];
    }

    /**
     * The attributes that are used for sorting.
     *
     * @return array
     */
    public function sortableBy() : array
    {
        return ['id', 'first_name', 'middle_name', 'options->key', 'meta.priority'];
    }
}
