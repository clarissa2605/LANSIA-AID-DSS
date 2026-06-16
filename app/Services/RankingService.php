<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\Lansia;
use Illuminate\Validation\ValidationException;
use DB;

class RankingService
{
    public function __construct(
        protected ValidationService $validationService
    ) {
    }

    /**
     * Calculate ranking with improved error handling
     * Returns both valid rankings and incomplete lansia information
     *
     * @return array ['ranking' => array, 'incomplete' => array, 'status' => string]
     * @throws ValidationException
     */
    public function hitung()
    {
        $kriteria = Kriteria::orderBy('id')->get();

        $lansia = Lansia::with(['penilaians', 'pengajuans'])
            ->orderBy('id')
            ->get();

        // Validate prerequisites
        if ($kriteria->isEmpty()) {
            throw ValidationException::withMessages([
                'kriteria' => 'Kriteria belum tersedia.'
            ]);
        }

        if ($lansia->isEmpty()) {
            throw ValidationException::withMessages([
                'lansia' => 'Data lansia belum tersedia.'
            ]);
        }

        $totalBobot = $kriteria->sum('bobot');

        if ($totalBobot <= 0) {
            throw ValidationException::withMessages([
                'bobot' => 'Bobot kriteria belum dihitung. Jalankan AHP terlebih dahulu.'
            ]);
        }

        // Separate lansia into complete and incomplete
        $complete = [];
        $incomplete = [];

        foreach ($lansia as $item) {
            $penilaianCount = $item->penilaians->count();
            $totalKriteria = $kriteria->count();

            if ($penilaianCount < $totalKriteria) {
                $missingKriteria = $kriteria
                    ->whereNotIn('id', $item->penilaians->pluck('kriteria_id'))
                    ->pluck('nama')
                    ->toArray();

                $incomplete[] = [
                    'lansia_id' => $item->id,
                    'nama' => $item->nama,
                    'penilaian_count' => $penilaianCount,
                    'total_kriteria' => $totalKriteria,
                    'missing_kriteria' => $missingKriteria,
                    'status' => 'INCOMPLETE'
                ];
            } else {
                $complete[] = $item;
            }
        }

        // If no complete lansia, return early with warning
        if (empty($complete)) {
            return [
                'ranking' => [],
                'incomplete' => $incomplete,
                'status' => 'NO_COMPLETE_DATA',
                'message' => 'Tidak ada lansia dengan penilaian lengkap. Silakan lengkapi penilaian terlebih dahulu.',
                'total_lansia' => count($lansia),
                'incomplete_count' => count($incomplete)
            ];
        }

        // Calculate ranking only for complete lansia
        $nilai = $this->ambilNilai(
            $kriteria,
            $complete
        );

        $ranking = [];

        foreach ($complete as $item) {
            $skor = 0;
            $detail = [];

            foreach ($kriteria as $kriterium) {
                $nilaiAwal = $nilai[$item->id][$kriterium->id] ?? 0;
                $bobot = (float) $kriterium->bobot;
                $nilaiAkhir = $nilaiAwal * $bobot;
                $skor += $nilaiAkhir;

                $detail[] = [
                    'kriteria_id' => $kriterium->id,
                    'kode' => $kriterium->kode,
                    'nama' => $kriterium->nama,
                    'atribut' => $kriterium->atribut,
                    'nilai' => $nilaiAwal,
                    'bobot' => round($bobot, 4),
                    'nilai_akhir' => round($nilaiAkhir, 4)
                ];
            }

            $request = $item->pengajuans
                ->where('status', '!=', 'disalurkan')
                ->sortByDesc(function ($request) {
                    return $this->urgencyWeight($request->urgensi);
                })
                ->first();

            $requestUrgency = $request?->urgensi;
            $requestJenis = $request?->jenis;
            $requestDate = $request?->created_at?->toDateString();
            $urgencyBonus = $this->hitungUrgensiBonus($requestUrgency);

            $ranking[] = [
                'lansia_id' => $item->id,
                'nama' => $item->nama,
                'umur' => $item->umur,
                'kecamatan' => $item->kecamatan,
                'status_tinggal' => $item->status_tinggal,
                'skor' => round($skor + $urgencyBonus, 4),
                'priority_status' => $this->statusPrioritas($skor + $urgencyBonus),
                'request_status' => $request?->status ?? 'pending',
                'pengajuan_id' => $request?->id,
                'jenis_bantuan' => $requestJenis ?? 'Bantuan Sosial',
                'urgensi' => $requestUrgency ?? 'rendah',
                'tanggal_pengajuan' => $requestDate ?? now()->toDateString(),
                'urgency_bonus' => round($urgencyBonus, 4),
                'detail' => $detail
            ];
        }

        usort($ranking, function ($a, $b) {
            if ($a['skor'] === $b['skor']) {
                // Tiebreaker: older age first
                if ($a['umur'] !== $b['umur']) {
                    return $a['umur'] <=> $b['umur'];
                }
                // Then: earlier submission date
                if ($a['tanggal_pengajuan'] !== $b['tanggal_pengajuan']) {
                    return $a['tanggal_pengajuan'] <=> $b['tanggal_pengajuan'];
                }
                // Finally: alphabetical name
                return strcmp($a['nama'], $b['nama']);
            }

            return $b['skor'] <=> $a['skor'];
        });

        foreach ($ranking as $index => $item) {
            $ranking[$index]['rank'] = $index + 1;
        }

        return [
            'ranking' => $ranking,
            'incomplete' => $incomplete,
            'status' => 'PARTIAL_DATA',
            'complete_count' => count($ranking),
            'incomplete_count' => count($incomplete),
            'total_lansia' => count($lansia)
        ];
    }

    private function ambilNilai(
        $kriteria,
        $lansia
    ) {
        $nilai = [];

        $missing = [];

        foreach ($lansia as $item) {

            $penilaian =
                $item->penilaians
                    ->keyBy('kriteria_id');

            foreach ($kriteria as $kriterium) {

                if (
                    !$penilaian->has(
                        $kriterium->id
                    )
                ) {
                    $missing[] =
                        $item->nama .
                        ' - ' .
                        $kriterium->nama;

                    continue;
                }

                $nilai[$item->id][$kriterium->id]
                    = (float)
                    $penilaian[$kriterium->id]->nilai;
            }
        }

        if (!empty($missing)) {

            throw ValidationException::withMessages([
                'penilaian' =>
                    'Penilaian belum lengkap: '
                    . implode(', ', $missing)
            ]);
        }

        return $nilai;
    }

    private function statusPrioritas(
        float $skor
    ) {
        if ($skor >= 4) {
            return 'Prioritas Utama';
        }

        if ($skor >= 3) {
            return 'Diprioritaskan';
        }

        if ($skor >= 2) {
            return 'Cukup';
        }

        return 'Rendah';
    }

    private function hitungUrgensiBonus(?string $urgensi): float
    {
        return $this->urgencyWeight($urgensi);
    }

    private function urgencyWeight(?string $urgensi): float
    {
        return match ($urgensi) {
            'tinggi' => 0.5,
            'sedang' => 0.3,
            'rendah' => 0.1,
            default => 0.0,
        };
    }
}
