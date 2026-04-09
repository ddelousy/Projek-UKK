<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;

class StrukController extends Controller
{
    public function cetak($id)
    {
        $transaksi = Transaksi::with(['kendaraan.tarif', 'areaParkir'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('struk', [
            'transaksi' => $transaksi
        ]);

        return $pdf->stream('struk-'.$transaksi->id.'.pdf');
    }
}