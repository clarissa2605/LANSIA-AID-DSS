<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index()
    {
        return response()->json(
            Penilaian::with([
                'lansia',
                'kriteria'
            ])->get()
        );
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'lansia_id' => 'required|exists:lansia,id',
        'nilai' => 'required|array'
    ]);

    foreach ($validated['nilai'] as $kriteriaId => $nilai) {

        Penilaian::updateOrCreate(
            [
                'lansia_id' => $validated['lansia_id'],
                'kriteria_id' => $kriteriaId
            ],
            [
                'nilai' => $nilai
            ]
        );
    }

    return response()->json([
        'message' => 'Penilaian berhasil disimpan'
    ]);
}

    public function show(Penilaian $penilaian)
    {
        return response()->json(
            $penilaian->load([
                'lansia',
                'kriteria'
            ])
        );
    }

    public function update(
        Request $request,
        Penilaian $penilaian
    )
    {
        $validated = $request->validate([
            'lansia_id' => 'required|exists:lansia,id',
            'kriteria_id' => 'required|exists:kriteria,id',
            'nilai' => 'required|numeric|min:0'
        ]);

        $penilaian->update($validated);

        return response()->json([
            'message' => 'Penilaian berhasil diperbarui',
            'data' => $penilaian
        ]);
    }

    public function destroy(Penilaian $penilaian)
    {
        $penilaian->delete();

        return response()->json([
            'message' => 'Penilaian berhasil dihapus'
        ]);
    }
}
