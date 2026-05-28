<?php

namespace App\Modules\Customers\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Customers\Requests\CreateCustomerRequest;
use App\Modules\Customers\Requests\UpdateCustomerRequest;
use App\Modules\Customers\Services\CustomerService;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function __construct(
        protected CustomerService $customerService,
    ) {}

    public function index(): JsonResponse
    {
        return $this->customerService->index(auth()->user());
    }

    public function store(CreateCustomerRequest $request): JsonResponse
    {
        return $this->customerService->store(auth()->user(), $request->validated());
    }

    public function show(int $id): JsonResponse
    {
        return $this->customerService->show(auth()->user(), $id);
    }

    public function update(UpdateCustomerRequest $request, int $id): JsonResponse
    {
        return $this->customerService->update(auth()->user(), $id, $request->validated());
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->customerService->destroy(auth()->user(), $id);
    }
}