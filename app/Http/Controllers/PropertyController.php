<?php

namespace App\Http\Controllers;

use App\Http\Requests\PropertyRequest;
use App\Http\Resources\PropertyResource;
use App\Http\Response\ApiResponse;
use App\Repositories\PropertyRepositoryInterface;
use App\Services\RentDistributionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    protected $propertyRepository;
    protected $rentDistributionService;

    public function __construct(PropertyRepositoryInterface $propertyRepository, RentDistributionService $rentDistributionService)
    {
        $this->propertyRepository = $propertyRepository;
        $this->rentDistributionService = $rentDistributionService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['name', 'address', 'min_rent', 'max_rent', 'owner_id']);

            // Get all properties or apply filters if provided
            $properties = $this->propertyRepository->all();

            if (!empty($filters)) {
                $properties = $this->propertyRepository->search($filters);
            }

            $propertiesResource = PropertyResource::collection($properties);
            return ApiResponse::success($propertiesResource);
        } catch (\Exception $e) {
            return (new ApiResponse())->handleExceptionErrors($e);
        }
    }

    public function store(PropertyRequest $request): JsonResponse
    {
        try {
            $property = $this->propertyRepository->create($request->all());
            return ApiResponse::success([
                new PropertyResource($property)
            ], config('messages.create_property_success'), ApiResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return (new ApiResponse())->handleExceptionErrors($e);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $property = $this->propertyRepository->find($id)->load('tenants');
            return ApiResponse::success(new PropertyResource($property));
        } catch (\Exception $e) {
            return (new ApiResponse())->handleExceptionErrors($e);
        }
    }

    public function update(PropertyRequest $request, $id): JsonResponse
    {
        try {
            $property = $this->propertyRepository->update($id, $request->all());
            return ApiResponse::success([
                new PropertyResource($property)
            ], config('messages.update_property_success'), ApiResponse::HTTP_OK);
        } catch (\Exception $e) {
            return (new ApiResponse())->handleExceptionErrors($e);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->propertyRepository->delete($id);
            return ApiResponse::success([], config('messages.delete_property_success'), ApiResponse::HTTP_OK);
        } catch (\Exception $e) {
            return (new ApiResponse())->handleExceptionErrors($e);
        }
    }

    public function rentDistribution($id): JsonResponse
    {
        try {
            return $this->rentDistributionService->calculateRentDistribution($id);
        } catch (\Exception $e) {
            return (new ApiResponse())->handleExceptionErrors($e);
        }
    }
}
