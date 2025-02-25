<?php

namespace App\Repositories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;

class TenantRepository implements TenantRepositoryInterface
{
    public function all(): Collection
    {
        return Tenant::with('property')->get();
    }

    public function find($id): Tenant
    {
        return Tenant::findOrFail($id);
    }

    public function create(array $data): Tenant
    {
        return Tenant::create($data);
    }

    public function update($id, array $data): Tenant
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->update($data);
        return $tenant;
    }

    public function delete($id): void
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->delete();
    }

    public function getMonthlyRentForTenants(array $tenantIds): array
    {
        $tenants = Tenant::whereIn('id', $tenantIds)->get();

        $monthlyRent = [];
        foreach ($tenants as $tenant) {
            $property = $tenant->property;
            $percentage = $tenant->rent_percentage ?? 0;
            $rentShare = ($property->rent_amount * $percentage) / 100;

            $monthlyRent[] = [
                'tenant_id' => $tenant->id,
                'monthly_rent' => $rentShare,
            ];
        }

        return $monthlyRent;
    }
}
