@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Form Pengajuan Penjualan FA</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('fa-penjualan.index') }}">Fixed Asset</a></li>
                <li class="breadcrumb-item active">Penjualan</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('fa-penjualan.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No FA <span class="text-danger">*</span></label>
                                <input type="text" name="no_fa" class="form-control @error('no_fa') is-invalid @enderror" value="{{ old('no_fa') }}" placeholder="FA-001" required>
                                @error('no_fa') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama FA <span class="text-danger">*</span></label>
                                <input type="text" name="nama_fa" class="form-control @error('nama_fa') is-invalid @enderror" value="{{ old('nama_fa') }}" placeholder="Nama FA" required>
                                @error('nama_fa') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Qty <span class="text-danger">*</span></label>
                                <input type="number" name="qty" class="form-control @error('qty') is-invalid @enderror" value="{{ old('qty', 1) }}" min="1" required>
                                @error('qty') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>NBV (Nilai Buku) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="nbv" class="form-control @error('nbv') is-invalid @enderror" value="{{ old('nbv') }}" placeholder="0.00" required>
                                @error('nbv') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Harga Jual <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" value="{{ old('harga_jual') }}" placeholder="0.00" required>
                                @error('harga_jual') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jenis <span class="text-danger">*</span></label>
                                <select name="jenis" class="form-control @error('jenis') is-invalid @enderror" required>
                                    <option value="internal" {{ old('jenis') == 'internal' ? 'selected' : '' }}>Internal</option>
                                    <option value="external" {{ old('jenis') == 'external' ? 'selected' : '' }}>External</option>
                                </select>
                                @error('jenis') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Keterangan <span class="text-danger">*</span></label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" placeholder="Alasan penjualan" required>{{ old('keterangan') }}</textarea>
                        @error('keterangan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Ajukan</button>
                            <a href="{{ route('fa-penjualan.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
