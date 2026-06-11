<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PerbandinganKriteria;
use Illuminate\Http\Request;

class PerbandinganKriteriaController extends Controller
{
    public function index()
    {
        return response()->json(
            PerbandinganKriteria::with([
                'kriteria1',
                'kriteria2'
            ])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kriteria_1_id' => 'required|exists:kriteria,id',
            'kriteria_2_id' => 'required|exists:kriteria,id',
            'nilai' => 'required|numeric|min:0.1111|max:9'
        ]);

        if ($validated['kriteria_1_id'] == $validated['kriteria_2_id']) {
            return response()->json([
                'message' => 'Kriteria tidak boleh dibandingkan dengan dirinya sendiri'
            ], 422);
        }

        $perbandingan = PerbandinganKriteria::create($validated);

        return response()->json([
            'message' => 'Perbandingan berhasil ditambahkan',
            'data' => $perbandingan
        ], 201);
    }

   public function show(PerbandinganKriteria $perbandingan_kriterium)
{
    return response()->json(
        $perbandingan_kriterium->load([
            'kriteria1',
            'kriteria2'
        ])
    );
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'kriteria_1_id' => 'required|exists:kriteria,id',
        'kriteria_2_id' => 'required|exists:kriteria,id',
        'nilai' => 'required|numeric|min:0.1111|max:9'
    ]);

    $perbandingan = PerbandinganKriteria::findOrFail($id);

    $perbandingan->update($validated);

    return response()->json([
        'message' => 'Perbandingan berhasil diperbarui',
        'data' => $perbandingan
    ]);
}
}
