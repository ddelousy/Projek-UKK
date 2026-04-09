<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    protected $table = 'tarif';

    protected $fillable = [
        'jenis_kendaraan',
        'tarif_per_jam',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function kendaraan()
    {
        return $this->hasMany(Kendaraan::class);
    }
}
