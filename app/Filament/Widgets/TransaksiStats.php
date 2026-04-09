<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransaksiStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Kendaraan Masuk', Transaksi::where('status', 'IN')->count())
            ->description('Kendaraan sedang parkir.')
            ->descriptionIcon('heroicon-m-paper-airplane')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('primary'),
            Stat::make('Kendaraan Keluar', Transaksi::where('status', 'OUT')->count())
            ->description('Kendaraan keluar area parkir.')
            ->descriptionIcon('heroicon-m-arrow-left-start-on-rectangle')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('warning'),
            Stat::make('Kendaraan Selesai', Transaksi::where('status', 'DONE')->count())
            ->description('Transaksi yang telah selesai.')
            ->descriptionIcon('heroicon-m-document-check')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('success'),
            Stat::make('Kendaraan Selesai', 'RP. ' .number_format(Transaksi::where('status', 'DONE')->sum('biaya_total'),0))
            ->description('Pendapatan yang di dapat.')
            ->descriptionIcon('heroicon-m-banknotes')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('danger'),
        ];
    }
}
