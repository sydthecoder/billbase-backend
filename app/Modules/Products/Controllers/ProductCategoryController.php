<?php

namespace App\Modules\Products\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Products\Requests\CreateProductCategoryRequest;
use App\Modules\Products\Requests\UpdateProductCategoryRequest;
use App\Modules\Products\Services\ProductCategoryService;
use Illuminate\Http\JsonResponse;

class ProductCategoryController extends Controller
{
    public function __construct(
        protected ProductCategoryService $productCategoryService,
    ) {}

    public function index(): JsonResponse
    {
        return $this->productCategoryService->index(auth()->user());
    }

    public function store(CreateProductCategoryRequest $request): JsonResponse
    {
        return $this->productCategoryService->store(auth()->user(), $request->validated());
    }

    public function update(UpdateProductCategoryRequest $request, int $id): JsonResponse
    {
        return $this->productCategoryService->update(auth()->user(), $request->validated(), $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->productCategoryService->destroy(auth()->user(), $id);
    }
}