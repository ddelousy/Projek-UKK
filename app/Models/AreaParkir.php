<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaParkir extends Model
{
    protected $table = 'area_parkir';

    protected $fillable = [
        'nama_area',
        'kapasitas',
        'terisi',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
