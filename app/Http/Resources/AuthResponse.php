<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "token" => (string) $this->getToken(),
            "token_type" => "bearer",
            "expires_in" => $this->getPayload()->get('exp'),
            "user" => new UserResource($this->user()),
            "permission" => $this->user()->getAllPermissions()->pluck('name')
        ];
    }
}
