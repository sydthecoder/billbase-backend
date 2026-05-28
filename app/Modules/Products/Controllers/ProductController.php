<?php

namespace App\Modules\Products\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Products\Requests\CreateProductRequest;
use App\Modules\Products\Requests\UpdateProductRequest;
use App\Modules\Products\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
    ) {}

    public function index(): JsonResponse
    {
        return $this->productService->index(auth()->user());
    }

    public function store(CreateProductRequest $request): JsonResponse
    {
        return $this->productService->store(auth()->user(), $request->validated());
    }

    public function show(int $id): JsonResponse
    {
        return $this->productService->show(auth()->user(), $id);
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        return $this->productService->update(auth()->user(), $request->validated(), $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->productService->destroy(auth()->user(), $id);
    }
}