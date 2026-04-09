<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Transaksi;

use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\ExportAction;
use Filament\Actions\Action;

use Filament\Tables\Columns\TextColumn;

use App\Filament\Exports\TransaksiExporter;

class TransaksiDoneTable extends Component
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
                    ->where('status', 'done')
            )

            ->columns([
                TextColumn::make('card_id')
                    ->label('Card'),

                TextColumn::make('waktu_masuk')
                    ->dateTime(),

                TextColumn::make('waktu_keluar')
                    ->dateTime(),

                TextColumn::make('durasi_jam')
                    ->suffix(' Jam')
                    ->placeholder('-'),

                TextColumn::make('biaya_total')
                    ->money('IDR')
                    ->placeholder('-'),

                TextColumn::make('status')
                    ->badge(),
            ])

            ->headerActions([
                ExportAction::make()
                    ->label('Unduh Data Laporan')
                    ->exporter(TransaksiExporter::class),
            ])

            ->recordActions([
                Action::make('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('struk.cetak', $record->id))
                    ->openUrlInNewTab()
            ]);
    }

    public function render()
    {
        return view('livewire.transaksi-done-table');
    }
}