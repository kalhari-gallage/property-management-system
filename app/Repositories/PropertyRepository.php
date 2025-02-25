<?php

namespace App\Repositories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Collection;

class PropertyRepository implements PropertyRepositoryInterface
{
    public function all(): Collection
    {
        return Property::all();
    }

    public function find($id): Property
    {
        return Property::findOrFail($id);
    }

    public function create(array $data): Property
    {
        return Property::create($data);
    }

    public function update($id, array $data): Property
    {
        $property = Property::findOrFail($id);
        $property->update($data);
        return $property;
    }

    public function delete($id): void
    {
        $property = Property::findOrFail($id);
        $property->delete();
    }

    public function search($filters): Collection
    {
        $query = Property::query();

        // Filter by name
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        // Filter by address
        if (isset($filters['address'])) {
            $query->where('address', 'like', '%' . $filters['address'] . '%');
        }

        // Filter by rent amount (min)
        if (isset($filters['min_rent'])) {
            $query->where('rent_amount', '>=', $filters['min_rent']);
        }

        // Filter by rent amount (max)
        if (isset($filters['max_rent'])) {
            $query->where('rent_amount', '<=', $filters['max_rent']);
        }

        // Filter by owner ID
        if (isset($filters['owner_id'])) {
            $query->where('owner_id', $filters['owner_id']);
        }

        return $query->get();
    }
}
