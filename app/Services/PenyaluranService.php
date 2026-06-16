<?php

namespace App\Services;

use App\Models\PengajuanBantuan;

class PenyaluranService
{
    public function __construct(
        protected RankingService $rankingService
    ) {
    }

    /**
     * Get list of applicants with ranking information
     * Gracefully handles cases where ranking is incomplete
     *
     * @return array
     */
    public function getApplicants(): array
    {
        try {
            $rankingResult = $this->rankingService->hitung();

            // Handle no complete data case
            if ($rankingResult['status'] === 'NO_COMPLETE_DATA') {
                // Still show pengajuan but without ranking scores
                return $this->buildApplicantsList([], []);
            }

            $ranking = collect($rankingResult['ranking'])
                ->keyBy('lansia_id');

            $pengajuans = PengajuanBantuan::with('lansia')
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->buildApplicantsList($pengajuans, $ranking);
        } catch (\Exception $e) {
            // If ranking fails, still return pengajuan without scores
            $pengajuans = PengajuanBantuan::with('lansia')
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->buildApplicantsList($pengajuans, collect([]));
        }
    }

    /**
     * Build applicants list with proper sorting and formatting
     *
     * @param mixed $pengajuans
     * @param mixed $ranking
     * @return array
     */
    private function buildApplicantsList($pengajuans, $ranking): array
    {
        $applicants = collect($pengajuans)->map(function (PengajuanBantuan $pengajuan) use ($ranking) {
            $rankData = $ranking->get($pengajuan->lansia_id, []);

            return [
                'rank' => $rankData['rank'] ?? null,
                'pengajuan_id' => $pengajuan->id,
                'lansia_id' => $pengajuan->lansia_id,
                'nama' => $pengajuan->lansia?->nama ?? null,
                'skor' => isset($rankData['skor']) ? round($rankData['skor'], 4) : null,
                'jenis' => $pengajuan->jenis,
                'urgensi' => $pengajuan->urgensi,
                'status' => $pengajuan->status,
                'tanggal_pengajuan' => $pengajuan->created_at?->toDateString(),
            ];
        });

        return $applicants
            ->sort(function ($a, $b) {
                // Sort by rank if available
                if (!is_null($a['rank']) && !is_null($b['rank'])) {
                    if ($a['rank'] === $b['rank']) {
                        return strcmp($a['tanggal_pengajuan'] ?? '', $b['tanggal_pengajuan'] ?? '');
                    }
                    return $a['rank'] <=> $b['rank'];
                }

                // If rank not available, sort by submission date (newer first)
                return strcmp($b['tanggal_pengajuan'] ?? '', $a['tanggal_pengajuan'] ?? '');
            })
            ->values()
            ->all();
    }
}
