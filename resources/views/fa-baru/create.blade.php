@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Pengajuan Fixed Asset</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('fa-baru.index') }}">Fixed Asset</a></li>
                <li class="breadcrumb-item active">Pengajuan Baru</li>
            </ol>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Pengajuan Fixed Asset</h4>
                <p class="text-muted mb-0">Pilih kategori dan tambahkan item FA secara dinamis.</p>
            </div>
            <div class="card-body">
                <form action="{{ route('fa-baru.store') }}" method="POST">
                    @csrf

                    {{-- Kategori --}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kategori">Kategori FA <span class="text-danger">*</span></label>
                                <select class="form-control @error('kategori') is-invalid @enderror" id="kategori" name="kategori">
                                    <option value="umum" {{ old('kategori') == 'umum' ? 'selected' : '' }}>Umum</option>
                                    <option value="it" {{ old('kategori') == 'it' ? 'selected' : '' }}>IT / Elektronik</option>
                                    <option value="kendaraan" {{ old('kategori') == 'kendaraan' ? 'selected' : '' }}>Kendaraan (R2/R4)</option>
                                </select>
                                @error('kategori')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipe_kendaraan">Tipe Kendaraan (jika kendaraan)</label>
                                <input type="text" class="form-control @error('tipe_kendaraan') is-invalid @enderror"
                                       id="tipe_kendaraan" name="tipe_kendaraan" value="{{ old('tipe_kendaraan') }}" placeholder="R2 / R4 / COP">
                                @error('tipe_kendaraan')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="is_cop">COP (khusus R4)</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_cop" name="is_cop" value="1" {{ old('is_cop') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_cop">Ya, ini adalah R4 COP</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3">Daftar Item FA</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="fa-items-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>No. FA</th>
                                    <th>Nama FA</th>
                                    <th>Merk / Type / Spesifikasi</th>
                                    <th>Qty</th>
                                    <th>Estimasi Harga</th>
                                    <th style="width: 80px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="fa-items-body">
                                <tr class="fa-item-row">
                                    <td>
                                        <input type="text" class="form-control form-control-sm @error('items.0.no_fa') is-invalid @enderror"
                                               name="items[0][no_fa]" value="{{ old('items.0.no_fa') }}" placeholder="FA-001">
                                        @error('items.0.no_fa')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm @error('items.0.nama_fa') is-invalid @enderror"
                                               name="items[0][nama_fa]" value="{{ old('items.0.nama_fa') }}" placeholder="Nama FA">
                                        @error('items.0.nama_fa')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm @error('items.0.spesifikasi') is-invalid @enderror"
                                               name="items[0][spesifikasi]" value="{{ old('items.0.spesifikasi') }}" placeholder="Merk/Type/Spesifikasi">
                                        @error('items.0.spesifikasi')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm @error('items.0.qty') is-invalid @enderror"
                                               name="items[0][qty]" value="{{ old('items.0.qty', 1) }}" min="1">
                                        @error('items.0.qty')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control form-control-sm @error('items.0.estimasi_harga') is-invalid @enderror"
                                               name="items[0][estimasi_harga]" value="{{ old('items.0.estimasi_harga') }}" placeholder="0.00">
                                        @error('items.0.estimasi_harga')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-secondary btn-sm" id="add-row"><i class="fas fa-plus"></i> Tambah Baris</button>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Ajukan FA</button>
                            <a href="{{ route('fa-baru.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div><!-- end row -->
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let rowIndex = 1;

        // Tambah baris
        $('#add-row').click(function() {
            let newRow = `
                <tr class="fa-item-row">
                    <td>
                        <input type="text" class="form-control form-control-sm" name="items[${rowIndex}][no_fa]" placeholder="FA-001">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="items[${rowIndex}][nama_fa]" placeholder="Nama FA">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="items[${rowIndex}][spesifikasi]" placeholder="Merk/Type/Spesifikasi">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="items[${rowIndex}][qty]" value="1" min="1">
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control form-control-sm" name="items[${rowIndex}][estimasi_harga]" placeholder="0.00">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash-alt"></i></button>
                    </td>
                </tr>
            `;
            $('#fa-items-body').append(newRow);
            rowIndex++;
        });

        // Hapus baris (setidaknya satu baris tersisa)
        $(document).on('click', '.remove-row', function() {
            if ($('#fa-items-body tr').length > 1) {
                $(this).closest('tr').remove();
            } else {
                alert('Minimal satu baris item harus ada.');
            }
        });
    });
</script>
@endpush
