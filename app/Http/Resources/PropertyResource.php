<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'rent_amount' => floatval($this->rent_amount),
            'owner_id' => $this->owner_id,
            'tenants' => TenantResource::collection($this->whenLoaded('tenants')),
        ];
    }
}
