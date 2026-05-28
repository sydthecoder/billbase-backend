<?php

namespace App\Modules\Products\Services;

use App\Models\ProductCategory;
use App\Models\User;
use App\Modules\Products\Resources\ProductCategoryResource;
use Illuminate\Http\JsonResponse;

class ProductCategoryService
{
    public function index(User $user): JsonResponse
    {
        $categories = ProductCategory::where('organization_id', $user->organization_id)
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => ProductCategoryResource::collection($categories),
        ]);
    }

    public function store(User $user, array $data): JsonResponse
    {
        $exists = ProductCategory::where('organization_id', $user->organization_id)
            ->where('name', $data['name'])
            ->exists();

        if ($exists) {
            return response()->json([
                'status'  => 'error',
                'message' => 'A category with this name already exists.',
            ], 422);
        }

        $category = ProductCategory::create([
            ...$data,
            'organization_id' => $user->organization_id,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Category created.',
            'data'    => new ProductCategoryResource($category),
        ], 201);
    }

    public function update(User $user, int $id, array $data): JsonResponse
    {
        $category = ProductCategory::where('organization_id', $user->organization_id)
            ->findOrFail($id);

        if (isset($data['name'])) {
            $exists = ProductCategory::where('organization_id', $user->organization_id)
                ->where('name', $data['name'])
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'A category with this name already exists.',
                ], 422);
            }
        }

        $category->update($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Category updated.',
            'data'    => new ProductCategoryResource($category->fresh()),
        ]);
    }

    public function destroy(User $user, int $id): JsonResponse
    {
        $category = ProductCategory::where('organization_id', $user->organization_id)
            ->findOrFail($id);

        // Hard delete — SET NULL fires at DB level on products
        $category->forceDelete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Category deleted.',
        ]);
    }
}