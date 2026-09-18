@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Edit Service FA Non-Kendaraan</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('am-service-fa-non-kendaraan.index') }}">AM Jasa</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('am-service-fa-non-kendaraan.update', $amServiceFaNonKendaraan) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label for="email_atasan">Email Atasan (Approval Level 1) <span class="text-danger">*</span></label>
                        <input type="email" name="email_atasan" class="form-control @error('email_atasan') is-invalid @enderror"
                               value="{{ old('email_atasan', $amServiceFaNonKendaraan->email_atasan) }}" placeholder="email atasan">
                        <small class="text-muted">Email atasan yang akan menerima permintaan approval level 1.</small>
                        @error('email_atasan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>No FA <span class="text-danger">*</span></label>
                        <input type="text" name="no_fa" class="form-control @error('no_fa') is-invalid @enderror"
                               value="{{ old('no_fa', $amServiceFaNonKendaraan->no_fa) }}" required>
                        @error('no_fa') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Nama / Merk / Spesifikasi <span class="text-danger">*</span></label>
                        <input type="text" name="nama_fa_merk_spesifikasi" class="form-control @error('nama_fa_merk_spesifikasi') is-invalid @enderror"
                               value="{{ old('nama_fa_merk_spesifikasi', $amServiceFaNonKendaraan->nama_fa_merk_spesifikasi) }}" required>
                        @error('nama_fa_merk_spesifikasi') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Perbaikan / Penggantian <span class="text-danger">*</span></label>
                        <textarea name="perbaikan_penggantian" class="form-control @error('perbaikan_penggantian') is-invalid @enderror"
                                  rows="3" required>{{ old('perbaikan_penggantian', $amServiceFaNonKendaraan->perbaikan_penggantian) }}</textarea>
                        @error('perbaikan_penggantian') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Keluhan <span class="text-danger">*</span></label>
                        <textarea name="keluhan" class="form-control @error('keluhan') is-invalid @enderror"
                                  rows="3" required>{{ old('keluhan', $amServiceFaNonKendaraan->keluhan) }}</textarea>
                        @error('keluhan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('am-service-fa-non-kendaraan.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
