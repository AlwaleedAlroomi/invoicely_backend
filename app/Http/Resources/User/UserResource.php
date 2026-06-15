<?php

namespace App\Http\Resources\User;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->hashid,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'team_id' => $this->current_team_id ? Hashids::encode($this->current_team_id) : null,
            'branch_id' => $this->branch_id ? Hashids::encode($this->branch_id) : null,
        ];
    }
}
