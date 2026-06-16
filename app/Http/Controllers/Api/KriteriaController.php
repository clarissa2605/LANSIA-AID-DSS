<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        return response()->json(
            Kriteria::orderBy('kode')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:kriteria,kode',
            'nama' => 'required',
            'atribut' => 'required|in:benefit,cost',
        ]);

        $kriteria = Kriteria::create([
            'kode' => $validated['kode'],
            'nama' => $validated['nama'],
            'atribut' => $validated['atribut'],
            'bobot' => 0
        ]);

        return response()->json([
            'message' => 'Kriteria berhasil ditambahkan',
            'data' => $kriteria
        ], 201);
    }

    public function show(Kriteria $kriteria)
    {
        return response()->json($kriteria);
    }

    public function update(Request $request, Kriteria $kriteria)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:kriteria,kode,' . $kriteria->id,
            'nama' => 'required',
            'atribut' => 'required|in:benefit,cost',
        ]);

        $kriteria->update($validated);

        return response()->json([
            'message' => 'Kriteria berhasil diupdate',
            'data' => $kriteria
        ]);
    }

    public function destroy(Kriteria $kriteria)
    {
        $kriteria->delete();

        return response()->json([
            'message' => 'Kriteria berhasil dihapus'
        ]);
    }
}
