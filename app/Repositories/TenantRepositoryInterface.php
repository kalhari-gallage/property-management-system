<?php

namespace App\Repositories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;

interface TenantRepositoryInterface
{
    public function all(): Collection;
    public function find($id): Tenant;
    public function create(array $data): Tenant;
    public function update($id, array $data): Tenant;
    public function delete($id): void;
    public function getMonthlyRentForTenants(array $tenantIds): array;
}
