<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'time_start' => $this->time_start,
            'time_end' => $this->time_end,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user'=> $this->user
        ];
    }
}
