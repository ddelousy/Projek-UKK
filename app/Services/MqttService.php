<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use App\Models\Transaksi;
use App\Models\Tarif;

class MqttService
{
    public function subscribe()
    {
        $mqtt = new MqttClient('broker.hivemq.com', 1883, 'laravel-' . uniqid());
        $mqtt->connect();

        logger("🔥 Listening MQTT...");

        /*
        ======================
        🚗 ENTRY RFID
        ======================
        */
        $mqtt->subscribe('parking/hana/entry/rfid', function ($topic, $message) use ($mqtt) {

            logger("=== ENTRY ===");
            logger("RAW: " . $message);

            $data = json_decode($message, true);

            if (!$data || !isset($data['rfid'])) {
                logger("❌ FORMAT SALAH");
                return;
            }

            $card = strtoupper(trim($data['rfid']));
            logger("CARD: $card");

            // ❗ cek sudah parkir (pakai card_id langsung)
            $cek = Transaksi::where('card_id', $card)
                ->where('status', 'in')
                ->first();

            if ($cek) {
                logger("⚠️ Sudah parkir");
                $mqtt->publish('parking/hana/lcd', 'Sudah Parkir|', 0);
                return;
            }

            // ✅ simpan transaksi
            Transaksi::create([
                'card_id' => $card,
                'area_parkir_id' => 1,
                'waktu_masuk' => now(),
                'status' => 'in'
            ]);

            logger("✅ MASUK BERHASIL");

            // 🔓 buka palang
            $mqtt->publish('parking/hana/entry/servo', 'OPEN', 0);

            // 📺 LCD
            $mqtt->publish('parking/hana/lcd', 'Selamat Datang! Silakan Masuk', 0);

        }, 0);

        /*
        ======================
        🚗 EXIT RFID
        ======================
        */
        $mqtt->subscribe('parking/hana/exit/rfid', function ($topic, $message) use ($mqtt) {

            logger("=== EXIT ===");
            logger("RAW: " . $message);

            $data = json_decode($message, true);

            if (!$data || !isset($data['rfid'])) {
                logger("❌ FORMAT SALAH");
                return;
            }

            $card = strtoupper(trim($data['rfid']));
            logger("CARD: $card");

            // 🔍 cari transaksi aktif
            $transaksi = Transaksi::where('card_id', $card)
                ->where('status', 'in')
                ->latest()
                ->first();

            if (!$transaksi) {
                logger("⚠️ Tidak sedang parkir");
                $mqtt->publish('parking/hana/lcd', 'Tidak Sedang Parkir|', 0);
                return;
            }

            $keluar = now();

            // hitung durasi
            $durasi = max(1, ceil($transaksi->waktu_masuk->diffInMinutes($keluar) / 60));

            // 🔥 ambil tarif ID = 1
            $tarif = Tarif::find(1)?->tarif_per_jam ?? 0;

            $biaya = $durasi * $tarif;

            // update transaksi
            $transaksi->update([
                'waktu_keluar' => $keluar,
                'durasi_jam' => $durasi,
                'biaya_total' => $biaya,
                'status' => 'out'
            ]);

            logger("✅ KELUAR | BIAYA: $biaya");

            // 📺 LCD tampil bayar
            $mqtt->publish('parking/hana/lcd', "Total Bayar|Rp $biaya", 0);

        }, 0);

        $mqtt->loop(true);
    }
}