<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Services\AhpService;

class AhpController extends Controller
{
    public function hitung(AhpService $ahpService)
    {
        $bobot = $ahpService->hitung();

        return response()->json([
            'message' => 'AHP berhasil dihitung',
            'bobot' => $bobot
        ]);
    }

    public function bobot()
    {
        return response()->json(
            Kriteria::select(
                'id',
                'kode',
                'nama',
                'bobot'
            )->get()
        );
    }

    public function konsistensi(AhpService $ahpService)
    {
        return response()->json([
            'message' => 'Konsistensi AHP berhasil dihitung',
            'data' => $ahpService->konsistensi()
        ]);
    }
}
