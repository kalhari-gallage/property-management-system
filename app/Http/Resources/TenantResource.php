<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'property_id' => $this->property_id,
            'rent_percentage' => $this->rent_percentage ? floatval($this->rent_percentage) : null,
            'property' => new PropertyResource($this->whenLoaded('property')),
        ];
    }
}
