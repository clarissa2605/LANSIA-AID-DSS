<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    protected $table = 'penilaian';

    protected $fillable = [
        'lansia_id',
        'kriteria_id',
        'nilai',
    ];

    public function lansia(): BelongsTo
    {
        return $this->belongsTo(
            Lansia::class,
            'lansia_id'
        );
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(
            Kriteria::class,
            'kriteria_id'
        );
    }
}
