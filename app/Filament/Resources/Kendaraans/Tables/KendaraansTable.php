<?php

namespace App\Filament\Resources\Kendaraans\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KendaraansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plat_nomor')->searchable(),
                Select::make('jenis_kendaraan')
                    ->options([
                        'motor' => 'Motor',
                        'mobil' => 'Mobil',
                    ])
                    ->required(),
                TextColumn::make('warna'),
                TextColumn::make('pemilik'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
