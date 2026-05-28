<?php

namespace App\Modules\Plans\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Modules\Plans\Resources\PlanResource;
use Illuminate\Http\JsonResponse;

class PlanController extends Controller
{
    public function index(): JsonResponse
    {
        $plans = Plan::where('is_active', true)->orderBy('price')->get();

        return response()->json([
            'status' => 'success',
            'data'   => PlanResource::collection($plans),
        ]);
    }
}