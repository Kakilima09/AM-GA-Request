@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Edit Pengajuan FA</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('fa-baru.index') }}">Fixed Asset</a></li>
                <li class="breadcrumb-item active">Edit</li>
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
                <h4 class="card-title">Edit Form FA</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('fa-baru.update', $faBaru) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label for="email_atasan">Email Atasan (Approval Level 1) <span class="text-danger">*</span></label>
                        <input type="email" name="email_atasan" class="form-control @error('email_atasan') is-invalid @enderror"
                               value="{{ old('email_atasan', $faBaru->email_atasan) }}" placeholder="email atasan">
                        <small class="text-muted">Email atasan yang akan menerima permintaan approval level 1.</small>
                        @error('email_atasan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="no_fa">No. FA <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('no_fa') is-invalid @enderror"
                                       id="no_fa" name="no_fa" value="{{ old('no_fa', $faBaru->no_fa) }}">
                                @error('no_fa') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nama_fa">Nama FA <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_fa') is-invalid @enderror"
                                       id="nama_fa" name="nama_fa" value="{{ old('nama_fa', $faBaru->nama_fa) }}">
                                @error('nama_fa') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="merk_type_spesifikasi">Merk / Type / Spesifikasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('merk_type_spesifikasi') is-invalid @enderror"
                                       id="merk_type_spesifikasi" name="merk_type_spesifikasi" value="{{ old('merk_type_spesifikasi', $faBaru->merk_type_spesifikasi) }}">
                                @error('merk_type_spesifikasi') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="qty">Qty <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('qty') is-invalid @enderror"
                                       id="qty" name="qty" value="{{ old('qty', $faBaru->qty) }}" min="1">
                                @error('qty') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="estimasi_harga">Estimasi Harga <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('estimasi_harga') is-invalid @enderror"
                                       id="estimasi_harga" name="estimasi_harga" value="{{ old('estimasi_harga', $faBaru->estimasi_harga) }}">
                                @error('estimasi_harga') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="kategori">Kategori <span class="text-danger">*</span></label>
                                <select class="form-control @error('kategori') is-invalid @enderror" id="kategori" name="kategori">
                                    <option value="umum" {{ old('kategori', $faBaru->kategori) == 'umum' ? 'selected' : '' }}>Umum</option>
                                    <option value="it" {{ old('kategori', $faBaru->kategori) == 'it' ? 'selected' : '' }}>IT / Elektronik</option>
                                    <option value="kendaraan" {{ old('kategori', $faBaru->kategori) == 'kendaraan' ? 'selected' : '' }}>Kendaraan</option>
                                </select>
                                @error('kategori') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipe_kendaraan">Tipe Kendaraan</label>
                                <input type="text" class="form-control" id="tipe_kendaraan" name="tipe_kendaraan"
                                       value="{{ old('tipe_kendaraan', $faBaru->tipe_kendaraan) }}" placeholder="R2/R4/COP">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_cop" name="is_cop" value="1"
                                       {{ old('is_cop', $faBaru->is_cop) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_cop">COP (R4)</label>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5>Detail Item FA (tabel dinamis)</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="fa-items-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>No. FA</th>
                                    <th>Nama FA</th>
                                    <th>Spesifikasi</th>
                                    <th>Qty</th>
                                    <th>Estimasi Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="fa-items-body">
                                @foreach ($items as $index => $item)
                                <tr class="fa-item-row">
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="items[{{ $index }}][no_fa]"
                                               value="{{ old('items.'.$index.'.no_fa', $item->no_fa) }}" placeholder="FA-001">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="items[{{ $index }}][nama_fa]"
                                               value="{{ old('items.'.$index.'.nama_fa', $item->nama_fa) }}" placeholder="Nama FA">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="items[{{ $index }}][spesifikasi]"
                                               value="{{ old('items.'.$index.'.spesifikasi', $item->spesifikasi) }}" placeholder="Spesifikasi">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" name="items[{{ $index }}][qty]"
                                               value="{{ old('items.'.$index.'.qty', $item->qty) }}" min="1">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control form-control-sm" name="items[{{ $index }}][estimasi_harga]"
                                               value="{{ old('items.'.$index.'.estimasi_harga', $item->estimasi_harga) }}" placeholder="0.00">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-secondary btn-sm" id="add-row"><i class="fas fa-plus"></i> Tambah Baris</button>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Update FA</button>
                            <a href="{{ route('fa-baru.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let rowIndex = {{ count($items) }};

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
                        <input type="text" class="form-control form-control-sm" name="items[${rowIndex}][spesifikasi]" placeholder="Spesifikasi">
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

        // Hapus baris
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
