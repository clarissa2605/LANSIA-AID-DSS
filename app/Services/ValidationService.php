<?php

namespace App\Services;

use App\Models\Lansia;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\PengajuanBantuan;

/**
 * Validation helper service for complex business rules
 */
class ValidationService
{
    /**
     * Check if a lansia has complete penilaian for all kriteria
     *
     * @param Lansia $lansia
     * @return array ['is_complete' => bool, 'missing' => array]
     */
    public function checkPenilaianComplete(Lansia $lansia): array
    {
        $totalKriteria = Kriteria::count();

        if ($totalKriteria === 0) {
            return [
                'is_complete' => false,
                'missing' => ['kriteria' => 'Tidak ada kriteria yang tersedia'],
                'reason' => 'KRITERIA_EMPTY'
            ];
        }

        $penilaianCount = Penilaian::where('lansia_id', $lansia->id)->count();

        if ($penilaianCount < $totalKriteria) {
            $missingKriteria = Kriteria::whereNotIn(
                'id',
                Penilaian::where('lansia_id', $lansia->id)
                    ->pluck('kriteria_id')
            )->pluck('nama')->toArray();

            return [
                'is_complete' => false,
                'missing' => $missingKriteria,
                'count' => $penilaianCount,
                'total' => $totalKriteria,
                'reason' => 'PENILAIAN_INCOMPLETE'
            ];
        }

        return [
            'is_complete' => true,
            'missing' => [],
            'reason' => 'PENILAIAN_COMPLETE'
        ];
    }

    /**
     * Check if all lansia have complete penilaian
     *
     * @return array ['is_complete' => bool, 'incomplete_lansia' => array]
     */
    public function checkAllLansiaPenilaianComplete(): array
    {
        $totalKriteria = Kriteria::count();

        if ($totalKriteria === 0) {
            return [
                'is_complete' => false,
                'reason' => 'KRITERIA_EMPTY',
                'incomplete_lansia' => []
            ];
        }

        $lansiaWithIncomplete = [];

        $allLansia = Lansia::all();

        foreach ($allLansia as $lansia) {
            $validation = $this->checkPenilaianComplete($lansia);

            if (!$validation['is_complete']) {
                $lansiaWithIncomplete[] = [
                    'lansia_id' => $lansia->id,
                    'nama' => $lansia->nama,
                    'missing_count' => $validation['total'] - $validation['count'],
                    'missing_kriteria' => $validation['missing']
                ];
            }
        }

        return [
            'is_complete' => empty($lansiaWithIncomplete),
            'incomplete_count' => count($lansiaWithIncomplete),
            'incomplete_lansia' => $lansiaWithIncomplete,
            'reason' => empty($lansiaWithIncomplete) ? 'ALL_COMPLETE' : 'SOME_INCOMPLETE'
        ];
    }

    /**
     * Check if AHP calculation is complete
     *
     * @return array ['is_complete' => bool, 'reason' => string]
     */
    public function checkAhpComplete(): array
    {
        $totalKriteria = Kriteria::count();

        if ($totalKriteria === 0) {
            return [
                'is_complete' => false,
                'reason' => 'KRITERIA_EMPTY'
            ];
        }

        // Check if all kriteria have bobot > 0
        $kriteriaWithoutBobot = Kriteria::where('bobot', '<=', 0)->count();

        if ($kriteriaWithoutBobot > 0) {
            return [
                'is_complete' => false,
                'reason' => 'AHP_NOT_CALCULATED',
                'message' => 'Bobot kriteria belum dihitung. Jalankan AHP terlebih dahulu.'
            ];
        }

        return [
            'is_complete' => true,
            'reason' => 'AHP_COMPLETE'
        ];
    }

    /**
     * Validate pengajuan status transition
     *
     * @param string $currentStatus
     * @param string $newStatus
     * @return array ['is_valid' => bool, 'reason' => string]
     */
    public function validateStatusTransition(string $currentStatus, string $newStatus): array
    {
        $validTransitions = [
            'pending' => ['diproses', 'disalurkan'],
            'diproses' => ['disalurkan'],
            'disalurkan' => [], // terminal state
        ];

        if (!isset($validTransitions[$currentStatus])) {
            return [
                'is_valid' => false,
                'reason' => 'INVALID_CURRENT_STATUS',
                'message' => "Status saat ini '{$currentStatus}' tidak valid"
            ];
        }

        if (!in_array($newStatus, $validTransitions[$currentStatus])) {
            return [
                'is_valid' => false,
                'reason' => 'INVALID_TRANSITION',
                'message' => "Tidak dapat mengubah status dari '{$currentStatus}' ke '{$newStatus}'",
                'allowed_transitions' => $validTransitions[$currentStatus]
            ];
        }

        return [
            'is_valid' => true,
            'reason' => 'VALID_TRANSITION'
        ];
    }

    /**
     * Check if lansia can accept new pengajuan
     * Returns whether a lansia can submit new assistance request
     *
     * Business rule: Only ONE active (non-disalurkan) pengajuan per lansia at a time
     *
     * @param int $lansiaId
     * @return array ['can_apply' => bool, 'reason' => string, 'active_pengajuan' => ?PengajuanBantuan]
     */
    public function canApplyForAssistance(int $lansiaId): array
    {
        $activePengajuan = PengajuanBantuan::where('lansia_id', $lansiaId)
            ->where('status', '!=', 'disalurkan')
            ->first();

        if ($activePengajuan) {
            return [
                'can_apply' => false,
                'reason' => 'ACTIVE_PENGAJUAN_EXISTS',
                'message' => 'Lansia sudah memiliki pengajuan yang sedang aktif',
                'active_pengajuan' => $activePengajuan
            ];
        }

        return [
            'can_apply' => true,
            'reason' => 'CAN_APPLY',
            'message' => 'Lansia dapat mengajukan bantuan'
        ];
    }

    /**
     * Check if lansia can be deleted
     * Prevent deletion if has active pengajuan
     *
     * @param int $lansiaId
     * @return array ['can_delete' => bool, 'reason' => string]
     */
    public function canDeleteLansia(int $lansiaId): array
    {
        $activePengajuan = PengajuanBantuan::where('lansia_id', $lansiaId)
            ->where('status', '!=', 'disalurkan')
            ->count();

        if ($activePengajuan > 0) {
            return [
                'can_delete' => false,
                'reason' => 'HAS_ACTIVE_PENGAJUAN',
                'message' => 'Tidak dapat menghapus lansia yang memiliki pengajuan aktif. Selesaikan semua pengajuan terlebih dahulu.'
            ];
        }

        return [
            'can_delete' => true,
            'reason' => 'CAN_DELETE',
            'message' => 'Lansia dapat dihapus'
        ];
    }
}
