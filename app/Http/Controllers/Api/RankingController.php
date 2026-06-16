<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RankingService;
use Illuminate\Validation\ValidationException;

class RankingController extends Controller
{
    public function hitung(RankingService $rankingService)
    {
        try {
            $result = $rankingService->hitung();

            return response()->json([
                'message' => 'Ranking berhasil dihitung',
                'metode' => 'AHP Weighted Sum',
                'rumus' => 'sum(nilai_kriteria * bobot_ahp)',
                'data' => $result
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Gagal menghitung ranking',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(RankingService $rankingService)
    {
        try {
            $result = $rankingService->hitung();

            // Return ranking array (maintain backward compatibility)
            // If incomplete data, still return the ranking array with metadata
            if ($result['status'] === 'NO_COMPLETE_DATA') {
                return response()->json([
                    'data' => [],
                    'status' => 'NO_COMPLETE_DATA',
                    'message' => $result['message'],
                    'incomplete' => $result['incomplete']
                ]);
            }

            return response()->json([
                'data' => $result['ranking'],
                'status' => $result['status'],
                'complete_count' => $result['complete_count'],
                'incomplete_count' => $result['incomplete_count'],
                'incomplete' => $result['incomplete']
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Gagal memuat ranking',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
