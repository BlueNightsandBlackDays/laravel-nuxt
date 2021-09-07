<?php

namespace App\Http\Controllers\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Orion\Http\Controllers\RelationController;
use App\Models\User;
use Orion\Http\Requests\Request;

class UserAttendancesController extends RelationController
{
    protected $model = User::class;
    protected $relation = 'attendanceEntries';

    /**
     * Builds Eloquent query for fetching relation entities in index method.
     *
     * @param Request $request
     * @param Model $parentEntity
     * @param array $requestedRelations
     * @return Relation
     */
    protected function buildIndexFetchQuery(Request $request, Model $parentEntity, array $requestedRelations): Relation
    {
        $query = parent::buildRelationFetchQuery($request, $parentEntity, $requestedRelations);

        $query->orderByDesc('created_at');

        return $query;
    }
}
