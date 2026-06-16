<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lansia extends Model
{
    protected $table = 'lansia';

    protected $fillable = [
        'nik',
        'nama',
        'umur',
        'jenis_kelamin',
        'alamat',
        'kecamatan',
        'status_tinggal',
        'kondisi_kesehatan',
        'kondisi_rumah',
        'kategori_penghasilan'
    ];

    public function penilaians(): HasMany
    {
        return $this->hasMany(
            Penilaian::class,
            'lansia_id'
        );
    }

    public function pengajuanBantuan(): HasMany
    {
        return $this->hasMany(
            PengajuanBantuan::class,
            'lansia_id'
        );
    }

    public function pengajuans(): HasMany
    {
        return $this->hasMany(
            PengajuanBantuan::class,
            'lansia_id'
        );
    }
}
