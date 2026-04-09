<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Transaksi;
use App\Models\Tarif;

use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;

class TransaksiMasukTable extends Component
implements HasTable, HasForms, HasActions
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    #[On('transaksi-updated')]
    public function refreshTable()
    {
        $this->resetTable();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaksi::query()
                    ->where('status', 'in') 
            )

            ->columns([
                TextColumn::make('card_id')
                    ->label('Card ID'),

                TextColumn::make('waktu_masuk')
                    ->dateTime(),
            ])

            ->recordActions([
                Action::make('Keluar')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {

                        $keluar = now();

                        // durasi jam (minimal 1)
                        $durasi = $record->waktu_masuk
                            ? ceil($record->waktu_masuk->diffInMinutes($keluar) / 60)
                            : 1;

                        $durasi = max(1, $durasi);

                        // 🔥 sama kayak service MQTT
                        $tarif = Tarif::find(1)?->tarif_per_jam ?? 0;

                        $total = $durasi * $tarif;

                        $record->update([
                            'waktu_keluar' => $keluar,
                            'durasi_jam' => $durasi,
                            'biaya_total' => $total,
                            'status' => 'out',
                        ]);

                        $this->dispatch('transaksi-updated');
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.transaksi-masuk-table');
    }
}