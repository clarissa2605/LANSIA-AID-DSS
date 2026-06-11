<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PenyaluranService;

class PenyaluranController extends Controller
{
    public function index(PenyaluranService $penyaluranService)
    {
        return response()->json([
            'data' => $penyaluranService->getApplicants(),
        ]);
    }
}
