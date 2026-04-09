<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use App\Models\Transaksi;
use App\Models\Tarif;

class MqttListen extends Command
{
    protected $signature = 'mqtt:listen';
    protected $description = 'Listen MQTT RFID Parking';

    public function handle()
    {
        $this->info('🔥 MQTT Listener jalan... nunggu RFID');

        $mqtt = new MqttClient(
            'broker.hivemq.com',
            1883,
            'laravel-' . uniqid()
        );

        $mqtt->connect();

        /*
        ======================
        🚗 ENTRY RFID
        ======================
        */
        $mqtt->subscribe('parking/hana/entry/rfid', function ($topic, $message) use ($mqtt) {

            echo "\n=== ENTRY ===\n";
            echo "RAW: $message\n";

            $data = json_decode($message, true);

            if (!$data || !isset($data['rfid'])) {
                echo "❌ FORMAT SALAH\n";
                return;
            }

            $card = strtoupper(trim($data['rfid']));
            echo "CARD: $card\n";

            // cek sudah parkir
            $cek = Transaksi::where('card_id', $card)
                ->where('status', 'in')
                ->first();

            if ($cek) {
                echo "Kendaraan sudah parkir\n";
                $mqtt->publish('parking/hana/lcd', 'Sudah Parkir|', 0);
                return;
            }

            Transaksi::create([
                'card_id' => $card,
                'area_parkir_id' => 1,
                'waktu_masuk' => now(),
                'status' => 'in'
            ]);

            echo "✅ MASUK BERHASIL\n";

            $mqtt->publish('parking/hana/entry/servo', 'OPEN', 0);
            $mqtt->publish('parking/hana/lcd', 'Selamat Datang|Silakan Masuk', 0);

        }, 0);

        /*
        ======================
        🚗 EXIT RFID
        ======================
        */
        $mqtt->subscribe('parking/hana/exit/rfid', function ($topic, $message) use ($mqtt) {

            echo "\n=== EXIT ===\n";
            echo "RAW: $message\n";

            $data = json_decode($message, true);

            if (!$data || !isset($data['rfid'])) {
                echo "❌ FORMAT SALAH\n";
                return;
            }

            $card = strtoupper(trim($data['rfid']));
            echo "CARD: $card\n";

            $transaksi = Transaksi::where('card_id', $card)
                ->where('status', 'in')
                ->latest()
                ->first();

            if (!$transaksi) {
                echo "Tidak sedang parkir\n";
                $mqtt->publish('parking/hana/lcd', 'Tidak Sedang Parkir|', 0);
                return;
            }

            $keluar = now();

            $durasi = max(1, ceil($transaksi->waktu_masuk->diffInMinutes($keluar) / 60));
            $tarif = Tarif::find(1)?->tarif_per_jam ?? 0;
            $biaya = $durasi * $tarif;

            $transaksi->update([
                'waktu_keluar' => $keluar,
                'durasi_jam' => $durasi,
                'biaya_total' => $biaya,
                'status' => 'out' // 🔥 PENTING
            ]);

            echo "✅ KELUAR | BIAYA: $biaya\n";

            $mqtt->publish('parking/hana/lcd', "Total Bayar|Rp $biaya", 0);

        }, 0);

        $mqtt->loop(true);
    }
}