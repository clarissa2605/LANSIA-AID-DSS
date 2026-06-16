<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Lansia;
use App\Models\PengajuanBantuan;
use App\Services\RankingService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class DashboardController extends Controller
{
    public function index(RankingService $rankingService)
    {
        return response()->json([
            'message' => 'Dashboard berhasil dimuat',
            'statistik' => $this->statistikData(),
            'ranking_teratas' => $this->rankingTeratas($rankingService)
        ]);
    }

    public function statistik()
    {
        return response()->json([
            'message' => 'Statistik dashboard berhasil dimuat',
            'data' => $this->statistikData()
        ]);
    }

    private function statistikData()
    {
        $totalLansia = Lansia::count();
        $totalKriteria = Kriteria::count();

        $lansiaDenganJumlahPenilaian = Lansia::withCount('penilaians')
            ->get();

        $sudahDinilai = $totalKriteria > 0
            ? $lansiaDenganJumlahPenilaian
                ->where('penilaians_count', '>=', $totalKriteria)
                ->count()
            : 0;

        $bantuanDisalurkan = Schema::hasTable('pengajuan_bantuan')
            ? PengajuanBantuan::where('status', 'disalurkan')->count()
            : 0;

        return [
            'total_lansia' => $totalLansia,
            'total_kriteria' => $totalKriteria,
            'sudah_dinilai' => $sudahDinilai,
            'belum_dinilai' => $totalLansia - $sudahDinilai,
            'bobot_terhitung' => Kriteria::where('bobot', '>', 0)->count(),
            'bantuan_disalurkan' => $bantuanDisalurkan
        ];
    }

  private function rankingTeratas(RankingService $rankingService)
{
    try {

        $result = $rankingService->hitung();

        return collect($result['ranking'] ?? [])
            ->take(5)
            ->map(function ($item) {

                return [
                    'rank' => $item['rank'],
                    'lansia_id' => $item['lansia_id'],
                    'nama' => $item['nama'],
                    'umur' => $item['umur'],
                    'kecamatan' => $item['kecamatan'],
                    'status_tinggal' => $item['status_tinggal'],
                    'skor' => $item['skor'],
                    'priority_status' => $item['priority_status'],
                    'urgensi' => $item['urgensi'],
                    'request_status' => $item['request_status'],
                ];
            })
            ->values();

    } catch (ValidationException $exception) {

        return [];
    }
}
}
