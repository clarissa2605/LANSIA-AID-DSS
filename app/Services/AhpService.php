<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\PerbandinganKriteria;
use Illuminate\Validation\ValidationException;

class AhpService
{
    public function hitung()
    {
        $hasil = $this->hitungPrioritas();

        foreach ($hasil['kriteria'] as $item) {

            $item->update([
                'bobot' => $hasil['bobot'][$item->id]
            ]);
        }

        return $hasil['bobot'];
    }

    public function konsistensi()
    {
        $hasil = $this->hitungPrioritas();

        return $hasil['konsistensi'];
    }

    private function hitungPrioritas()
    {
        $kriteria = Kriteria::all();

        $n = $kriteria->count();

        if ($n === 0) {
            throw ValidationException::withMessages([
                'kriteria' => 'Kriteria belum tersedia.'
            ]);
        }

        $matrix = [];

        foreach ($kriteria as $row) {

            foreach ($kriteria as $col) {

                if ($row->id == $col->id) {
                    $matrix[$row->id][$col->id] = 1;
                    continue;
                }

                $perbandingan = PerbandinganKriteria::where(
                    'kriteria_1_id',
                    $row->id
                )
                ->where(
                    'kriteria_2_id',
                    $col->id
                )
                ->first();

                if ($perbandingan) {

                    $matrix[$row->id][$col->id]
                        = $perbandingan->nilai;

                } else {

                    $reverse = PerbandinganKriteria::where(
                        'kriteria_1_id',
                        $col->id
                    )
                    ->where(
                        'kriteria_2_id',
                        $row->id
                    )
                    ->first();

                    if (!$reverse) {
                        throw ValidationException::withMessages([
                            'perbandingan' => 'Perbandingan kriteria belum lengkap.'
                        ]);
                    }

                    $matrix[$row->id][$col->id]
                        = 1 / $reverse->nilai;
                }
            }
        }

        $columnTotals = [];

        foreach ($kriteria as $col) {

            $sum = 0;

            foreach ($kriteria as $row) {

                $sum += $matrix[$row->id][$col->id];
            }

            $columnTotals[$col->id] = $sum;
        }

        $normalized = [];

        foreach ($kriteria as $row) {

            foreach ($kriteria as $col) {

                $normalized[$row->id][$col->id]
                    = $matrix[$row->id][$col->id]
                    / $columnTotals[$col->id];
            }
        }

        $bobot = [];

        foreach ($kriteria as $row) {

            $sum = array_sum(
                $normalized[$row->id]
            );

            $bobot[$row->id]
                = $sum / $n;
        }

        return [
            'kriteria' => $kriteria,
            'bobot' => $bobot,
            'konsistensi' => $this->hitungKonsistensi(
                $kriteria,
                $matrix,
                $bobot
            )
        ];
    }

    private function hitungKonsistensi($kriteria, array $matrix, array $bobot)
    {
        $n = $kriteria->count();
        $lambdaTotal = 0;

        foreach ($kriteria as $row) {

            $weightedSum = 0;

            foreach ($kriteria as $col) {
                $weightedSum += $matrix[$row->id][$col->id] * $bobot[$col->id];
            }

            $lambdaTotal += $weightedSum / $bobot[$row->id];
        }

        $lambdaMax = $lambdaTotal / $n;
        $ci = $n > 1 ? max(0, ($lambdaMax - $n) / ($n - 1)) : 0;
        $ri = $this->randomIndex($n);
        $cr = $ri > 0 ? max(0, $ci / $ri) : 0;

        return [
            'jumlah_kriteria' => $n,
            'lambda_max' => round($lambdaMax, 4),
            'ci' => round($ci, 4),
            'ri' => $ri,
            'cr' => round($cr, 4),
            'batas_cr' => 0.1,
            'konsisten' => $cr <= 0.1
        ];
    }

    private function randomIndex(int $n)
    {
        $randomIndexes = [
            1 => 0,
            2 => 0,
            3 => 0.58,
            4 => 0.9,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.45,
            10 => 1.49,
        ];

        return $randomIndexes[$n] ?? 1.49;
    }
}
