<?php

namespace App\Modules\Lookup\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Plan;

class LookupController extends Controller
{
    public function banks(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data'   => config('lookup.south_african_banks'),
        ]);
    }

    public function accountTypes(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data'   => config('lookup.account_types'),
        ]);
    }

    public function provinces(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data'   => config('lookup.provinces'),
        ]);
    }

    public function paymentTerms(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data'   => config('lookup.payment_terms'),
        ]);
    }

    public function productUnits(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data'   => config('lookup.product_units'),
        ]);
    }
}