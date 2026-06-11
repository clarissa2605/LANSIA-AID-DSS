<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PengajuanBantuan;
use App\Services\ValidationService;
use Illuminate\Http\Request;

class PengajuanBantuanController extends Controller
{
    public function __construct(
        protected ValidationService $validationService
    ) {
    }

    public function index()
    {
        $pengajuans = PengajuanBantuan::with('lansia')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function (PengajuanBantuan $item) {
                return [
                    'id' => $item->id,
                    'lansia_id' => $item->lansia_id,
                    'nama' => $item->lansia?->nama,
                    'jenis' => $item->jenis,
                    'urgensi' => $item->urgensi,
                    'status' => $item->status,
                    'catatan' => $item->catatan,
                    'tanggal_pengajuan' => $item->created_at?->toDateString(),
                ];
            });

        return response()->json([
            'data' => $pengajuans
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lansia_id' => 'required|integer|exists:lansia,id',
            'jenis' => 'required|in:Bantuan Kesehatan,Bantuan Sosial',
            'urgensi' => 'required|in:rendah,sedang,tinggi',
            'catatan' => 'nullable|string',
        ]);

        $canApply = $this->validationService
            ->canApplyForAssistance(
                $validated['lansia_id']
            );

        if (!$canApply['can_apply']) {

            return response()->json([
                'message' => $canApply['message'],
                'reason' => $canApply['reason'],
                'active_pengajuan' =>
                    $canApply['active_pengajuan'] ?? null
            ], 409);
        }

        $pengajuan = PengajuanBantuan::create([
            'lansia_id' => $validated['lansia_id'],
            'jenis' => $validated['jenis'],
            'urgensi' => $validated['urgensi'],
            'status' => 'pending',
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return response()->json([
            'message' => 'Pengajuan bantuan berhasil dibuat.',
            'data' => $pengajuan,
        ], 201);
    }

    public function show($id)
    {
        $pengajuan = PengajuanBantuan::with('lansia')
            ->findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $pengajuan->id,
                'lansia_id' => $pengajuan->lansia_id,
                'nama' => $pengajuan->lansia?->nama,
                'jenis' => $pengajuan->jenis,
                'urgensi' => $pengajuan->urgensi,
                'status' => $pengajuan->status,
                'catatan' => $pengajuan->catatan,
                'tanggal_pengajuan' =>
                    $pengajuan->created_at?->toDateString(),
            ]
        ]);
    }

public function update(Request $request, $id)
{
    $pengajuan = PengajuanBantuan::findOrFail($id);

    $validated = $request->validate([
        'jenis' => 'sometimes|in:Bantuan Kesehatan,Bantuan Sosial',
        'urgensi' => 'sometimes|in:rendah,sedang,tinggi',
        'status' => 'sometimes|in:pending,diproses,disalurkan,ditolak',
        'catatan' => 'nullable|string',
    ]);

    if (
        isset($validated['status']) &&
        $validated['status'] !== $pengajuan->status
    ) {
        $transition =
            $this->validationService
                ->validateStatusTransition(
                    $pengajuan->status,
                    $validated['status']
                );

        if (!$transition['is_valid']) {
            return response()->json([
                'message' => $transition['message'],
                'reason' => $transition['reason'],
            ], 422);
        }
    }

    $pengajuan->update($validated);

    return response()->json([
        'message' => 'Pengajuan berhasil diperbarui.',
        'data' => $pengajuan,
    ]);
}

    public function verifikasi($id)
    {
        $pengajuan = PengajuanBantuan::findOrFail($id);

        $pengajuan->update([
            'status' => 'diproses'
        ]);

        return response()->json([
            'message' => 'Pengajuan berhasil diverifikasi',
            'data' => $pengajuan
        ]);
    }

    public function tolak($id)
    {
        $pengajuan = PengajuanBantuan::findOrFail($id);

        $pengajuan->update([
            'status' => 'ditolak'
        ]);

        return response()->json([
            'message' => 'Pengajuan berhasil ditolak',
            'data' => $pengajuan
        ]);
    }

    public function salurkan($id)
    {
        $pengajuan = PengajuanBantuan::findOrFail($id);

        $pengajuan->update([
            'status' => 'disalurkan'
        ]);

        return response()->json([
            'message' => 'Bantuan berhasil disalurkan',
            'data' => $pengajuan
        ]);
    }

    public function destroy($id)
    {
        $pengajuan = PengajuanBantuan::findOrFail($id);

        if ($pengajuan->status === 'disalurkan') {

            return response()->json([
                'message' =>
                    'Tidak dapat menghapus pengajuan yang sudah disalurkan.',
                'reason' =>
                    'DISALURKAN_CANNOT_DELETE'
            ], 409);
        }

        $pengajuan->delete();

        return response()->json([
            'message' =>
                'Pengajuan bantuan berhasil dihapus.'
        ]);
    }
}
