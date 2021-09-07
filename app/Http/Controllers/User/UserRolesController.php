<?php

namespace App\Http\Controllers\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Orion\Http\Controllers\RelationController;
use Orion\Http\Requests\Request;
use App\Models\User;

class UserRolesController extends RelationController
{
    protected $model = User::class;
    protected $relation = 'roles';

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

        $query->orderBy('name')->pluck('name', 'name');

        return $query;
    }
}
