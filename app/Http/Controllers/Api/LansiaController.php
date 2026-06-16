<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lansia;
use App\Services\ValidationService;
use Illuminate\Http\Request;

class LansiaController extends Controller
{
    public function __construct(protected ValidationService $validationService)
    {
    }

    public function index()
    {
        return response()->json(
            Lansia::latest()->get()
        );
    }

    public function show($id)
    {
        return response()->json(
            Lansia::findOrFail($id)
        );
    }

    public function store(Request $request)
    {
       $validated = $request->validate([
        'nik' => 'required|unique:lansia,nik',
        'nama' => 'required|string|max:255',
        'umur' => 'required|integer|min:60',
        'jenis_kelamin' => 'required|in:L,P',
        'alamat' => 'required|string',
        'kecamatan' => 'required|string',

        'status_tinggal' =>
            'required|in:tinggal_sendiri,bersama_pasangan,bersama_keluarga,tinggal_di_panti',

        'kondisi_kesehatan' =>
            'required|in:sehat,penyakit_ringan,penyakit_kronis,disabilitas',

        'kondisi_rumah' =>
            'required|in:rumah_layak,rumah_cukup_layak,rumah_tidak_layak',

        'kategori_penghasilan' =>
            'required|in:tidak_memiliki_penghasilan,penghasilan_rendah,penghasilan_menengah',
    ]);

        $lansia = Lansia::create($validated);

        return response()->json([
            'message' => 'Data lansia berhasil ditambahkan',
            'data' => $lansia
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $lansia = Lansia::findOrFail($id);

        $validated = $request->validate([
    'nik' => 'required|unique:lansia,nik,' . $id,
    'nama' => 'required|string|max:255',
    'umur' => 'required|integer|min:60',
    'jenis_kelamin' => 'required|in:L,P',
    'alamat' => 'required|string',
    'kecamatan' => 'required|string',

    'status_tinggal' =>
        'required|in:tinggal_sendiri,bersama_pasangan,bersama_keluarga,tinggal_di_panti',

    'kondisi_kesehatan' =>
        'required|in:sehat,penyakit_ringan,penyakit_kronis,disabilitas',

    'kondisi_rumah' =>
        'required|in:rumah_layak,rumah_cukup_layak,rumah_tidak_layak',

    'kategori_penghasilan' =>
        'required|in:tidak_memiliki_penghasilan,penghasilan_rendah,penghasilan_menengah',
]);

        $lansia->update($validated);

        return response()->json([
            'message' => 'Data lansia berhasil diperbarui',
            'data' => $lansia
        ]);
    }

    public function destroy($id)
    {
        $lansia = Lansia::findOrFail($id);

        // Check if lansia can be deleted
        $canDelete = $this->validationService->canDeleteLansia($id);

        if (!$canDelete['can_delete']) {
            return response()->json([
                'message' => $canDelete['message'],
                'reason' => $canDelete['reason']
            ], 409); // 409 Conflict
        }

        $lansia->delete();

        return response()->json([
            'message' => 'Data lansia berhasil dihapus'
        ]);
    }
}
