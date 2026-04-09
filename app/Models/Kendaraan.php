<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $table = 'kendaraan';

    protected $fillable = [
        'plat_nomor',
        'jenis_kendaraan',
        'warna',
        'pemilik',
    ];

    protected static function booted()
    {
        static::creating(function ($kendaraan) {

            $tarif = Tarif::where('jenis_kendaraan', $kendaraan->jenis_kendaraan)->first();

            if ($tarif) {
                $kendaraan->tarif_id = $tarif->id;
            }
        });
    }

    public function tarif()
    {
        return $this->belongsTo(Tarif::class);
    }
}
