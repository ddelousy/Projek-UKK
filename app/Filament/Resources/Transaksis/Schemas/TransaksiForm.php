<?php

namespace App\Filament\Resources\Transaksis\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransaksiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /*
            ======================
            💳 CARD ID (RFID)
            ======================
            */
            TextInput::make('card_id')
                ->label('Card ID')
                ->helperText('Diisi otomatis dari RFID'),

            /*
            ======================
            🅿️ AREA PARKIR
            ======================
            */
            Select::make('area_parkir_id')
                ->relationship('areaParkir', 'nama_area')
                ->searchable()
                ->required(),

            /*
            ======================
            ⏰ WAKTU MASUK
            ======================
            */
            DateTimePicker::make('waktu_masuk')
                ->disabled()
                ->dehydrated(true)
                ->default(now()),

            /*
            ======================
            ⏰ WAKTU KELUAR
            ======================
            */
            DateTimePicker::make('waktu_keluar')
                ->disabled()
                ->dehydrated(true),
        ]);
    }
}