<?php

namespace App\Modules\Products\Services;

use App\Models\Product;
use App\Models\User;
use App\Modules\Products\Resources\ProductResource;
use Illuminate\Http\JsonResponse;

class ProductService
{
    public function index(User $user): JsonResponse
    {
        $products = Product::where('organization_id', $user->organization_id)
            ->with('category')
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => ProductResource::collection($products),
        ]);
    }

    public function store(User $user, array $data): JsonResponse
    {
        // Check name unique per org
        $exists = Product::where('organization_id', $user->organization_id)
            ->where('name', $data['name'])
            ->exists();

        if ($exists) {
            return response()->json([
                'status'  => 'error',
                'message' => 'A product with this name already exists.',
            ], 422);
        }

        $product = Product::create([
            ...$data,
            'organization_id' => $user->organization_id,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Product created.',
            'data'    => new ProductResource($product->load('category')),
        ], 201);
    }

    public function show(User $user, int $id): JsonResponse
    {
        $product = Product::where('organization_id', $user->organization_id)
            ->with('category')
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => new ProductResource($product),
        ]);
    }

    public function update(User $user, int $id, array $data): JsonResponse
    {
        $product = Product::where('organization_id', $user->organization_id)
            ->findOrFail($id);

        if (isset($data['name'])) {
            $exists = Product::where('organization_id', $user->organization_id)
                ->where('name', $data['name'])
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'A product with this name already exists.',
                ], 422);
            }
        }

        $product->update($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Product updated.',
            'data'    => new ProductResource($product->fresh()->load('category')),
        ]);
    }

    public function destroy(User $user, int $id): JsonResponse
    {
        $product = Product::where('organization_id', $user->organization_id)
            ->findOrFail($id);

        $product->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Product deleted.',
        ]);
    }
}