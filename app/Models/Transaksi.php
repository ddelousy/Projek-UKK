<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'card_id',
        'waktu_masuk',
        'waktu_keluar',
        'tarif_id',
        'durasi_jam',
        'biaya_total',
        'status',
        'area_parkir_id',
    ];

    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
    ];

    public function tarif()
    {
        return $this->belongsTo(Tarif::class);
    }

    public function areaParkir()
    {
        return $this->belongsTo(AreaParkir::class);
    }

}
