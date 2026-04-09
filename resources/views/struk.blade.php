<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Parkir</title>

    <style>
        body {
            font-family: monospace;
            font-size: 14px;
            width: 100%;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: auto;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
        }

        .bold {
            font-weight: bold;
        }

        .mt-1 { margin-top: 5px; }
        .mt-2 { margin-top: 10px; }
    </style>
</head>

<body>
<div class="container">

    <!-- HEADER -->
    <div class="center bold" style="font-size:16px;">
        SMART PARKING
    </div>

    <div class="center">
        Jl. Neo No. 127
    </div>

    <div class="center">
        Telp: 026-06062000
    </div>

    <div class="line"></div>

    <!-- DATA TRANSAKSI -->
    <div class="row">
        <span>ID</span>
        <span>#{{ $transaksi->id }}</span>
    </div>

    <div class="row">
        <span>Plat</span>
        <span>{{ $transaksi->kendaraan->plat_nomor ?? '-' }}</span>
    </div>

    <div class="row">
        <span>Jenis</span>
        <span>{{ $transaksi->kendaraan->jenis_kendaraan ?? '-' }}</span>
    </div>

    <div class="line"></div>

    <!-- WAKTU -->
    <div>
        <div>Masuk:</div>
        <div>{{ $transaksi->waktu_masuk }}</div>
    </div>

    <div class="mt-1">
        <div>Keluar:</div>
        <div>{{ $transaksi->waktu_keluar }}</div>
    </div>

    <div class="line"></div>

    <!-- BIAYA -->
    <div class="row">
        <span>Durasi</span>
        <span>{{ $transaksi->durasi_jam ?? 0 }} Jam</span>
    </div>

    <div class="row bold">
        <span>Total</span>
        <span>Rp {{ number_format($transaksi->biaya_total ?? 0, 0, ',', '.') }}</span>
    </div>

    <div class="line"></div>

    <!-- FOOTER -->
    <div class="center mt-2">
        TERIMA KASIH 🙏
    </div>

    <div class="center">
        HATI-HATI DI JALAN
    </div>

</div>
</body>
</html>