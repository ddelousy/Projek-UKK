<?php

namespace App\Filament\Exports;

use App\Models\Transaksi;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class TransaksiExporter extends Exporter
{
    protected static ?string $model = Transaksi::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('kendaraan.plat_nomor')
                ->label('Plat Nomor'),

            ExportColumn::make('kendaraan.jenis_kendaraan')
                ->label('Jenis Kendaraan'),

            ExportColumn::make('kendaraan.tarif.tarif_per_jam')
                ->label('Tarif / Jam'),

            ExportColumn::make('card_id')
                ->label('Card ID'),

            ExportColumn::make('areaParkir.nama_area')
                ->label('Area Parkir'),

            ExportColumn::make('waktu_masuk')
                ->label('Waktu Masuk'),

            ExportColumn::make('waktu_keluar')
                ->label('Waktu Keluar'),

            ExportColumn::make('durasi_jam')
                ->label('Durasi (Jam)'),

            ExportColumn::make('biaya_total')
                ->label('Total Bayar'),

            ExportColumn::make('status')
                ->label('Status'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export transaksi selesai: ' 
            . Number::format($export->successful_rows) 
            . ' data berhasil di-export.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' 
                . Number::format($failedRowsCount) 
                . ' data gagal.';
        }

        return $body;
    }
}