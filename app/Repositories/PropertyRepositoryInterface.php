<?php

namespace App\Repositories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Collection;

interface PropertyRepositoryInterface
{
    public function all(): Collection;
    public function find($id): Property;
    public function create(array $data): Property;
    public function update($id, array $data): Property;
    public function delete($id): void;
    public function search($filters): Collection;
}
