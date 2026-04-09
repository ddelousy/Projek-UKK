<?php

namespace App\Filament\Resources\Transaksis\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use App\Filament\Resources\Transaksis\TransaksiResource;
use App\Filament\Widgets\TransaksiStats;

class ListTransaksis extends ListRecords
{
    protected static string $resource = TransaksiResource::class;

    protected string $view = 'filament.transaksis.custom-list';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TransaksiStats::class
        ];
    }
}