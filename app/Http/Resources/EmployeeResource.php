<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'identification' => $this->identification,
            'jobRole' => $this->jobRole,
            'refunds' => $this->refunds,
            'createdAt' => $this->created_at
        ];
    }
}
