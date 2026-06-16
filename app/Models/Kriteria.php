<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    protected $table = 'kriteria';

    protected $fillable = [
        'kode',
        'nama',
        'atribut',
        'bobot',
    ];

    public function penilaians(): HasMany
    {
        return $this->hasMany(Penilaian::class);
    }

    public function perbandinganKriteriaSebagaiPertama(): HasMany
    {
        return $this->hasMany(
            PerbandinganKriteria::class,
            'kriteria_1_id'
        );
    }

    public function perbandinganKriteriaSebagaiKedua(): HasMany
    {
        return $this->hasMany(
            PerbandinganKriteria::class,
            'kriteria_2_id'
        );
    }
}
