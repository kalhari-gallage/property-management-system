<?php

namespace App\Services;

use App\Models\Property;
use App\Http\Response\ApiResponse;
use Illuminate\Http\JsonResponse;

class RentDistributionService
{
    /**
     * Calculate the rent distribution for a given property.
     *
     * @param int $propertyId
     */

    public function calculateRentDistribution(int $propertyId): JsonResponse
    {
        try {
            // Retrieve the property and its tenants
            $property = Property::with('tenants')->findOrFail($propertyId);

            $totalRent = $property->rent_amount;
            $tenants = $property->tenants;

            // Check if there are tenants for the property
            if ($tenants->isEmpty()) {
                // If no tenants, return an error response
                return ApiResponse::error(config('messages.no_tenants_for_property'), null, ApiResponse::HTTP_NOT_FOUND);
            }

            $rentDistribution = [];
            $totalPercentage = $tenants->sum(function ($tenant) {
                return $tenant->rent_percentage ?? 0;
            });

            // If no rent percentages are set, split the rent equally
            if ($totalPercentage == 0) {
                $equalShare = $totalRent / $tenants->count();
                foreach ($tenants as $tenant) {
                    $rentDistribution[] = [
                        'tenant_name' => $tenant->name,
                        'rent_share' => round($equalShare, 2),
                        'late_fee' => $this->calculateLateFee($tenant->id),
                    ];
                }
            } else {
                // Split the rent based on rent percentages
                foreach ($tenants as $tenant) {
                    $percentage = $tenant->rent_percentage ?? (100 / $tenants->count());
                    $rentShare = ($totalRent * $percentage) / 100;

                    $rentDistribution[] = [
                        'tenant_name' => $tenant->name,
                        'rent_share' => round($rentShare, 2),
                        'late_fee' => $this->calculateLateFee($tenant->id),
                    ];
                }
            }

            // Return a success response with the rent distribution data
            return ApiResponse::success([
                'property_name' => $property->name,
                'total_rent' => $totalRent,
                'tenants' => $rentDistribution,
            ]);
        } catch (\Exception $e) {
            // Handle unexpected exceptions and return an error response
            return (new ApiResponse())->handleExceptionErrors($e);
        }
    }

    //TODO need to calculate late fee
    // Calculate the late fee
    private function calculateLateFee(int $tenant): float
    {
        // There was no explicit mention of how it should be calculated. So return 0 for now
        return 0;
    }
}
