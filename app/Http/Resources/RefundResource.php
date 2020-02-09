<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RefundResource extends JsonResource
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
            'date' => $this->date,
            'type' => $this->type,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'description' => $this->description,
            'value' => $this->value,
            'receipt' => $this->receipt,
            'employee_id' => $this->employee_id,
            'employee' => $this->employee,
            'createdAt' => $this->created_at,
        ];
    }
}
