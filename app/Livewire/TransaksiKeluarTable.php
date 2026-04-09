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
use Filament\Actions\Action;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

use PhpMqtt\Client\MqttClient;

class TransaksiKeluarTable extends Component
implements HasTable, HasForms, HasActions
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    protected $listeners = ['transaksi-updated' => '$refresh'];

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
                    ->where('status', 'out')
            )

            ->columns([
                TextColumn::make('card_id')
                    ->label('Card'),

                TextColumn::make('waktu_keluar')
                    ->dateTime(),

                TextColumn::make('durasi_jam')
                    ->suffix(' Jam')
                    ->placeholder('-'),

                TextColumn::make('biaya_total')
                    ->money('IDR')
                    ->placeholder('-'),

                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'out',
                        'success' => 'done',
                    ]),
            ])

            ->recordActions([
                Action::make('Buka Palang')
                    ->color('success')
                    ->icon('heroicon-o-lock-open')
                    ->visible(fn ($record) => $record->status === 'out') // 🔥 penting
                    ->requiresConfirmation()
                    ->action(function ($record) {

                        try {
                            $mqtt = new MqttClient(
                                'broker.hivemq.com',
                                1883,
                                'filament-' . uniqid()
                            );

                            $mqtt->connect();

                            // buka palang
                            $mqtt->publish('parking/hana/exit/servo', 'OPEN', 0);

                            // lcd
                            $mqtt->publish('parking/hana/lcd', 'Terima Kasih|Selamat Jalan', 0);

                            $mqtt->disconnect();

                            // ✅ hanya update kalau MQTT sukses
                            $record->update([
                                'status' => 'done'
                            ]);

                        } catch (\Exception $e) {
                            logger('MQTT ERROR: ' . $e->getMessage());

                            // ❌ jangan update status kalau gagal
                        }

                        $this->dispatch('transaksi-updated');
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.transaksi-keluar-table');
    }
}