<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Lansia;
use App\Models\PengajuanBantuan;
use App\Services\RankingService;

class PetugasDashboardController extends Controller
{
    public function index(
        RankingService $rankingService
    ) {
        $totalKriteria = Kriteria::count();

        try {

    $result = $rankingService->hitung();

    $ranking = array_slice(
        $result['ranking'] ?? [],
        0,
        5
    );

} catch (\Exception $e) {

    $ranking = [];
}

        $belumDinilai = Lansia::withCount('penilaians')
            ->get()
            ->filter(fn ($lansia) =>
                $lansia->penilaians_count < $totalKriteria
            )
            ->count();

        return response()->json([
            'total_lansia' => Lansia::count(),

            'pending' => PengajuanBantuan::where(
                'status',
                'pending'
            )->count(),

            'diproses' => PengajuanBantuan::where(
                'status',
                'diproses'
            )->count(),

            'disalurkan' => PengajuanBantuan::where(
                'status',
                'disalurkan'
            )->count(),

            'belum_dinilai' => $belumDinilai,

            'ranking_teratas' => $ranking,
        ]);
    }

    public function tugas()
    {
        $totalKriteria = Kriteria::count();

        $tugas = Lansia::withCount('penilaians')
            ->get()
            ->filter(fn ($lansia) =>
                $lansia->penilaians_count < $totalKriteria
            )
            ->map(function ($lansia) use ($totalKriteria) {

                return [
                    'id' => $lansia->id,
                    'nama' => $lansia->nama,
                    'umur' => $lansia->umur,
                    'kecamatan' => $lansia->kecamatan,
                    'status_tinggal' => $lansia->status_tinggal,
                    'penilaian_terisi' => $lansia->penilaians_count,
                    'penilaian_dibutuhkan' => $totalKriteria,
                    'tugas' => 'Lengkapi Penilaian'
                ];
            })
            ->values();

        return response()->json($tugas);
    }

    public function lansiaBelumDinilai()
    {
        $totalKriteria = Kriteria::count();

        $data = Lansia::withCount('penilaians')
            ->get()
            ->filter(fn ($lansia) =>
                $lansia->penilaians_count < $totalKriteria
            )
            ->map(function ($lansia) use ($totalKriteria) {

                return [
                    'id' => $lansia->id,
                    'nama' => $lansia->nama,
                    'umur' => $lansia->umur,
                    'kecamatan' => $lansia->kecamatan,
                    'status_tinggal' => $lansia->status_tinggal,
                    'penilaian_terisi' => $lansia->penilaians_count,
                    'penilaian_dibutuhkan' => $totalKriteria
                ];
            })
            ->values();

        return response()->json($data);
    }

    public function prioritasBantuan(
        RankingService $rankingService
    ) {
        try {

            return response()->json(
                $rankingService->hitung()
            );

        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'data' => []
            ], 422);
        }
    }
public function monitoring()
{
    $data = PengajuanBantuan::with('lansia')
        ->orderByDesc('created_at')
        ->get()
        ->map(function ($item) {

            return [
                'id' => $item->id,
                'lansia_id' => $item->lansia_id,
                'nama' => $item->lansia?->nama,
                'jenis' => $item->jenis,
                'urgensi' => $item->urgensi,
                'status' => $item->status,
                'catatan' => $item->catatan,
                'tanggal_pengajuan' => optional(
                    $item->created_at
                )->toDateString()
            ];
        });

    return response()->json($data);
}

    public function riwayat()
    {
        $data = PengajuanBantuan::with('lansia')
            ->where('status', 'disalurkan')
            ->orderByDesc('updated_at')
            ->get()
            ->map(function ($item) {

                return [
                    'id' => $item->id,
                    'lansia_id' => $item->lansia_id,
                    'nama' => $item->lansia?->nama,
                    'jenis' => $item->jenis,
                    'urgensi' => $item->urgensi,
                    'status' => $item->status,
                    'catatan' => $item->catatan,
                    'tanggal_penyaluran' => optional(
                        $item->updated_at
                    )->toDateString()
                ];
            });

        return response()->json($data);
    }
}
