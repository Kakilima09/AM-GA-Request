<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BAST - {{ $bast->id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        .header { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px; }
        .section { margin-bottom: 15px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #000; padding: 5px; }
        .signature { margin-top: 30px; }
        .signature div { display: inline-block; width: 23%; text-align: center; }
        .signature img { max-width: 150px; height: auto; }
        .clause { margin-top: 20px; }
        .clause ol { margin-left: 20px; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #888; }
    </style>
</head>
<body>
    <div class="header">BERITA ACARA SERAH TERIMA</div>

    <div class="section">
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($bast->tanggal)->format('d/m/Y') }}</p>
        <p><strong>Pemohon:</strong> {{ $bast->nama_pemohon }} ({{ $bast->jabatan }})</p>
        <p><strong>Perusahaan:</strong> {{ $bast->company->name ?? '-' }}</p>
        <p><strong>Departemen:</strong> {{ $bast->department->name ?? '-' }}</p>
    </div>

    <div class="section">
        <strong>Penerimaan:</strong>
        <ul>
            @if($bast->penerimaan_fa) <li>Fixed Asset</li> @endif
            @if($bast->penerimaan_kendaraan_r2_r4) <li>Kendaraan R2/R4</li> @endif
            @if($bast->penerimaan_elektronik_it) <li>Elektronik / IT</li> @endif
            @if($bast->penerimaan_peralatan_kantor) <li>Peralatan Kantor</li> @endif
            @if($bast->penerimaan_lainnya) <li>Lainnya</li> @endif
            @if($bast->jasa_service_kendaraan) <li>Jasa Service Kendaraan</li> @endif
            @if($bast->jasa_renovasi) <li>Jasa Renovasi</li> @endif
            @if($bast->jasa_service_fa_non) <li>Jasa Service FA Non</li> @endif
            @if($bast->jasa_lainnya) <li>Jasa Lainnya</li> @endif
        </ul>
    </div>

    @if($bast->itemsFa->count())
        <div class="section">
            <h5>Item FA</h5>
            <table class="table">
                <thead><tr><th>No FA</th><th>Merk/Type/Spesifikasi</th><th>Qty</th><th>Kondisi</th><th>Keterangan</th></tr></thead>
                <tbody>
                    @foreach($bast->itemsFa as $item)
                    <tr>
                        <td>{{ $item->no_fa }}</td>
                        <td>{{ $item->merk_type_spesifikasi }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ ucfirst($item->kondisi) }}</td>
                        <td>{{ $item->keterangan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($bast->itemsJasa->count())
        <div class="section">
            <h5>Item Jasa</h5>
            <table class="table">
                <thead><tr><th>Tanggal Permintaan</th><th>Tanggal Selesai</th><th>Keterangan</th></tr></thead>
                <tbody>
                    @foreach($bast->itemsJasa as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_permintaan)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}</td>
                        <td>{{ $item->keterangan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="clause">
        <h5>Klausul Tanggung Jawab Penerima</h5>
        <ol>
            <li>Fixed Asset yang diberikan adalah sarana pendukung kerja dan milik perusahaan, bukan milik pribadi.</li>
            <li>Kondisi fisik, kelengkapan, dan fungsi Fixed Asset agar dapat dipergunakan dengan baik.</li>
            <li>Perbaikan apabila Fixed Asset mengalami kerusakan.</li>
            <li>Penggantian Fixed Asset dengan merk dan jenis yang sama atau sederajat, apabila terjadi kehilangan.</li>
            <li>Label Fixed Asset yang melekat berisi informasi pencatatan perusahaan.</li>
            <li>Pengembalian Fixed Asset apabila mengundurkan diri, mutasi, dll. dalam kondisi lengkap dan berfungsi baik.</li>
            <li>Semua biaya akibat kelalaian menjadi beban pribadi.</li>
        </ol>
    </div>

    <div class="signature">
        <div>
            <strong>Diserahkan oleh</strong><br>
            @if($bast->ttd_diserahkan) <img src="{{ $bast->ttd_diserahkan }}" width="150"> @endif
            <br>{{ $bast->diserahkan_oleh }}
        </div>
        <div>
            <strong>Diterima oleh</strong><br>
            @if($bast->ttd_diterima) <img src="{{ $bast->ttd_diterima }}" width="150"> @endif
            <br>{{ $bast->diterima_oleh }}
        </div>
        <div>
            <strong>Mengetahui</strong><br>
            @if($bast->ttd_mengetahui) <img src="{{ $bast->ttd_mengetahui }}" width="150"> @endif
            <br>{{ $bast->mengetahui ?? '-' }}
        </div>
        <div>
            <strong>Menyetujui</strong><br>
            @if($bast->ttd_menyetujui) <img src="{{ $bast->ttd_menyetujui }}" width="150"> @endif
            <br>{{ $bast->menyetujui ?? '-' }}
        </div>
    </div>

    <div class="footer">
        Dicetak pada {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
