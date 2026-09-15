@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Detail BAST</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('bast.index') }}">BAST</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Berita Acara Serah Terima</h4>
            </div>
            <div class="card-body">
                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($bast->tanggal)->format('d/m/Y') }}</p>
                <p><strong>Pemohon:</strong> {{ $bast->nama_pemohon }} ({{ $bast->jabatan }})</p>
                <p><strong>Perusahaan:</strong> {{ $bast->company->name ?? '-' }}</p>
                <p><strong>Departemen:</strong> {{ $bast->department->name ?? '-' }}</p>

                <hr>
                <h6>Penerimaan:</h6>
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

                @if($bast->itemsFa->count())
                    <h6>Item FA</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
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
                    <h6>Item Jasa</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
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

                <hr>
                <h6>Klausul Tanggung Jawab Penerima</h6>
                <ol>
                    <li>Fixed Asset yang diberikan adalah sarana pendukung kerja dan milik perusahaan, bukan milik pribadi.</li>
                    <li>Kondisi fisik, kelengkapan, dan fungsi Fixed Asset agar dapat dipergunakan dengan baik.</li>
                    <li>Perbaikan apabila Fixed Asset mengalami kerusakan.</li>
                    <li>Penggantian Fixed Asset dengan merk dan jenis yang sama atau sederajat, apabila terjadi kehilangan.</li>
                    <li>Label Fixed Asset yang melekat berisi informasi pencatatan perusahaan.</li>
                    <li>Pengembalian Fixed Asset apabila mengundurkan diri, mutasi, dll. dalam kondisi lengkap dan berfungsi baik.</li>
                    <li>Semua biaya akibat kelalaian menjadi beban pribadi.</li>
                </ol>

                <hr>
                <div class="row">
                    <div class="col-md-3"><strong>Diserahkan oleh:</strong> {{ $bast->diserahkan_oleh }}</div>
                    <div class="col-md-3"><strong>Diterima oleh:</strong> {{ $bast->diterima_oleh }}</div>
                    <div class="col-md-3"><strong>Mengetahui:</strong> {{ $bast->mengetahui ?? '-' }}</div>
                    <div class="col-md-3"><strong>Menyetujui:</strong> {{ $bast->menyetujui ?? '-' }}</div>
                </div>

                <!-- Tanda tangan digital -->
                <hr>
                <h6>Tanda Tangan Digital</h6>
                <div class="row">
                    @php
                        $signatureFields = [
                            'ttd_diserahkan' => 'Diserahkan oleh',
                            'ttd_diterima' => 'Diterima oleh',
                            'ttd_mengetahui' => 'Mengetahui',
                            'ttd_menyetujui' => 'Menyetujui',
                        ];
                    @endphp
                    @foreach($signatureFields as $field => $label)
                        <div class="col-md-3">
                            <p><strong>{{ $label }}</strong></p>
                            @if($bast->$field)
                                <img src="{{ $bast->$field }}" alt="Signature" style="max-width:100%; border:1px solid #ddd; padding:5px;">
                            @else
                                <p class="text-muted">Belum ditandatangani</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Aksi</h4>
            </div>
            <div class="card-body">
                <a href="{{ route('bast.export-pdf', $bast) }}" class="btn btn-danger btn-block" target="_blank">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </a>
                <a href="{{ route('bast.edit', $bast) }}" class="btn btn-warning btn-block">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('bast.destroy', $bast) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-block"><i class="fas fa-trash"></i> Hapus</button>
                </form>
                <a href="{{ route('bast.index') }}" class="btn btn-secondary btn-block">Kembali</a>
            </div>
        </div>

        {{-- Signature Pad (untuk menandatangani) --}}
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Tanda Tangan Digital</h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Pilih Penandatangan</label>
                    <select id="signatureField" class="form-control">
                        <option value="ttd_diserahkan">Diserahkan oleh</option>
                        <option value="ttd_diterima">Diterima oleh</option>
                        <option value="ttd_mengetahui">Mengetahui</option>
                        <option value="ttd_menyetujui">Menyetujui</option>
                    </select>
                </div>
                <div class="form-group">
                    <canvas id="signatureCanvas" width="300" height="150" style="border:1px solid #ccc; width:100%;"></canvas>
                </div>
                <div class="btn-group w-100">
                    <button class="btn btn-secondary btn-sm" id="clearSignature">Clear</button>
                    <button class="btn btn-primary btn-sm" id="saveSignature">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('signatureCanvas');
        const signaturePad = new SignaturePad(canvas);

        document.getElementById('clearSignature').addEventListener('click', function() {
            signaturePad.clear();
        });

        document.getElementById('saveSignature').addEventListener('click', function() {
            if (signaturePad.isEmpty()) {
                alert('Silakan tanda tangan terlebih dahulu.');
                return;
            }
            const field = document.getElementById('signatureField').value;
            const dataUrl = signaturePad.toDataURL('image/png');

            fetch('{{ route("bast.save-signature", $bast) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ field: field, signature: dataUrl })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Tanda tangan berhasil disimpan.');
                    location.reload();
                } else {
                    alert('Gagal menyimpan tanda tangan.');
                }
            })
            .catch(err => {
                alert('Terjadi kesalahan: ' + err.message);
            });
        });
    });
</script>
@endpush
