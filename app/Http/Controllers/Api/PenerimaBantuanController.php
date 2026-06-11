<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PengajuanBantuan;
use App\Services\RankingService;
use Illuminate\Http\Request;

class PenerimaBantuanController extends Controller
{
    public function index(
        Request $request,
        RankingService $rankingService
    ) {
        $validated = $request->validate([
            'limit' => 'sometimes|integer|min:1'
        ]);

        $limit = (int) ($validated['limit'] ?? 10);

        $penerima = collect($rankingService->hitung())
            ->take($limit)
            ->map(function ($item) {
                return [
                    'rank' => $item['rank'],
                    'pengajuan_id' => $item['pengajuan_id'] ?? null,
                    'lansia_id' => $item['lansia_id'],
                    'nama' => $item['nama'],
                    'skor' => $item['skor'],
                    'status' => $this->formatStatus($item['request_status'] ?? 'pending'),
                    'jenis' => $item['jenis_bantuan'] ?? 'Bantuan Sosial',
                    'urgensi' => $item['urgensi'] ?? 'rendah',
                    'tanggal_pengajuan' => $item['tanggal_pengajuan'] ?? now()->toDateString(),
                ];
            })
            ->values();

        return response()->json([
            'jumlah_penerima' => $penerima->count(),
            'data' => $penerima
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

    public function update(Request $request, $id)
    {
        $pengajuan = PengajuanBantuan::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,diproses,disalurkan',
            'catatan' => 'sometimes|nullable|string',
        ]);

        $pengajuan->update([
            'status' => $validated['status'],
            'catatan' => $validated['catatan'] ?? $pengajuan->catatan,
        ]);

        return response()->json([
            'message' => 'Status pengajuan bantuan berhasil diperbarui.',
            'data' => $pengajuan,
        ]);
    }

    private function formatStatus(string $status): string
    {
        return match ($status) {
            'pending' => 'Belum Disalurkan',
            'diproses' => 'Diproses',
            'disalurkan' => 'Disalurkan',
            default => $status,
        };
    }
}
