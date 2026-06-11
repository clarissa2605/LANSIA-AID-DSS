<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanBantuan extends Model
{
    protected $table = 'pengajuan_bantuan';

    protected $fillable = [
        'lansia_id',
        'jenis',
        'urgensi',
        'status',
        'catatan'
    ];

    public function lansia(): BelongsTo
    {
        return $this->belongsTo(
            Lansia::class,
            'lansia_id'
        );
    }
}
