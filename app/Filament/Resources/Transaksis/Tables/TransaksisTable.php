<?php

namespace App\Filament\Resources\Transaksis\Tables;

use App\Filament\Exports\TransaksiExporter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\Action;
use App\Models\Transaksi;
use App\Models\Tarif;
use Filament\Actions\ExportAction;

class TransaksisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('card_id')
                    ->label('Card ID')
                    ->searchable(),

                TextColumn::make('waktu_masuk')
                    ->label('Masuk')
                    ->dateTime(),

                TextColumn::make('waktu_keluar')
                    ->label('Keluar')
                    ->dateTime()
                    ->placeholder('-'),

                TextColumn::make('durasi_jam')
                    ->label('Durasi')
                    ->suffix(' Jam')
                    ->placeholder('-'),

                TextColumn::make('biaya_total')
                    ->money('IDR')
                    ->placeholder('-'),

                BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'in',
                        'success' => 'done',
                    ]),
            ])

            ->actions([

                /*
                ======================
                🚪 MASUK (MANUAL)
                ======================
                */
                Action::make('Masuk')
                    ->color('primary')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->visible(fn ($record) => $record->status === null)
                    ->requiresConfirmation()
                    ->action(function (Transaksi $record) {

                        $record->update([
                            'waktu_masuk' => now(),
                            'status' => 'in',
                        ]);
                    }),

                /*
                ======================
                🚪 KELUAR
                ======================
                */
                Action::make('Keluar')
                    ->color('danger')
                    ->icon('heroicon-o-arrow-left-circle')
                    ->visible(fn ($record) => $record->status === 'in')
                    ->requiresConfirmation()
                    ->action(function (Transaksi $record) {

                        $keluar = now();

                        // durasi jam (minimal 1)
                        $durasi = $record->waktu_masuk
                            ? ceil($record->waktu_masuk->diffInMinutes($keluar) / 60)
                            : 1;

                        $durasi = max(1, $durasi);

                        // 🔥 tarif fix ID = 1
                        $tarif = Tarif::find(1)?->tarif_per_jam ?? 0;

                        $record->update([
                            'waktu_keluar' => $keluar,
                            'durasi_jam' => $durasi,
                            'biaya_total' => $durasi * $tarif,
                            'status' => 'done',
                        ]);
                    }),

                /*
                ======================
                🔓 BUKA PALANG
                ======================
                */
                Action::make('Buka Palang')
                    ->color('success')
                    ->icon('heroicon-o-lock-open')
                    ->visible(fn ($record) => $record->status === 'done')
                    ->requiresConfirmation()
                    ->action(function (Transaksi $record) {
                        // ga ubah status lagi, cuma aksi
                        // (MQTT biasanya dihandle di tempat lain)
                    }),
            ])

            ->headerActions([
                ExportAction::make()->exporter(TransaksiExporter::class)
            ]);
    }
}