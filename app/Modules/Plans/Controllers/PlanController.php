<?php

namespace App\Modules\Plans\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Plans\Services\PlanService;
use Illuminate\Http\JsonResponse;

class PlanController extends Controller
{
    public function __construct(private PlanService $planService) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data'   => $this->planService->all(),
        ]);
    }
}