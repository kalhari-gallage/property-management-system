<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantRequest;
use App\Http\Resources\TenantResource;
use App\Http\Response\ApiResponse;
use App\Repositories\TenantRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    protected $tenantRepository;

    public function __construct(TenantRepositoryInterface $tenantRepository)
    {
        $this->tenantRepository = $tenantRepository;
    }

    public function index(): JsonResponse
    {
        try {
            $tenantsResource = TenantResource::collection($this->tenantRepository->all());
            return ApiResponse::success($tenantsResource);
        } catch (\Exception $e) {
            return (new ApiResponse())->handleExceptionErrors($e);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $tenant = $this->tenantRepository->find($id)->load('property');
            return ApiResponse::success(new TenantResource($tenant));
        } catch (\Exception $e) {
            return (new ApiResponse())->handleExceptionErrors($e);
        }
    }

    public function store(TenantRequest $request): JsonResponse
    {
        try {
            $tenant = $this->tenantRepository->create($request->all());
            return ApiResponse::success([
                new TenantResource($tenant)
            ], config('messages.create_tenant_success'), ApiResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return (new ApiResponse())->handleExceptionErrors($e);
        }
    }


    public function destroy($id): JsonResponse
    {
        try {
            $this->tenantRepository->delete($id);
            return ApiResponse::success([], config('messages.delete_tenant_success'), ApiResponse::HTTP_OK);
        } catch (\Exception $e) {
            return (new ApiResponse())->handleExceptionErrors($e);
        }
    }

    public function getMonthlyRent(Request $request): JsonResponse
    {
        try {

            $request->validate([
                'tenant_ids' => 'required|array',
                'tenant_ids.*' => 'exists:tenants,id',
            ]);

            $tenantIds = $request->input('tenant_ids');
            $monthlyRent = $this->tenantRepository->getMonthlyRentForTenants($tenantIds);
            return ApiResponse::success($monthlyRent);
        } catch (\Exception $e) {
            return (new ApiResponse())->handleExceptionErrors($e);
        }
    }
}
