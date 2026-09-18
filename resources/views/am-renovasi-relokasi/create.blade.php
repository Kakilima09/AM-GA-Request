@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Form Pengajuan Renovasi / Relokasi</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('am-renovasi.index') }}">AM Jasa</a></li>
                <li class="breadcrumb-item active">Renovasi / Relokasi</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('am-renovasi.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email_atasan">Email Atasan (Approval Level 1) <span class="text-danger">*</span></label>
                        <input type="email" name="email_atasan" class="form-control @error('email_atasan') is-invalid @enderror"
                               value="{{ old('email_atasan') }}" placeholder="email atasan">
                        <small class="text-muted">Email atasan yang akan menerima permintaan approval level 1.</small>
                        @error('email_atasan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lokasi Awal <span class="text-danger">*</span></label>
                                <input type="text" name="lokasi_awal" class="form-control @error('lokasi_awal') is-invalid @enderror"
                                       value="{{ old('lokasi_awal') }}" placeholder="Lokasi awal" required>
                                @error('lokasi_awal') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lokasi Tujuan <span class="text-danger">*</span></label>
                                <input type="text" name="lokasi_tujuan" class="form-control @error('lokasi_tujuan') is-invalid @enderror"
                                       value="{{ old('lokasi_tujuan') }}" placeholder="Lokasi tujuan" required>
                                @error('lokasi_tujuan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Spesifikasi Awal</label>
                                <textarea name="spesifikasi_awal" class="form-control @error('spesifikasi_awal') is-invalid @enderror"
                                          rows="3" placeholder="Spesifikasi awal">{{ old('spesifikasi_awal') }}</textarea>
                                @error('spesifikasi_awal') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Spesifikasi Tujuan</label>
                                <textarea name="spesifikasi_tujuan" class="form-control @error('spesifikasi_tujuan') is-invalid @enderror"
                                          rows="3" placeholder="Spesifikasi tujuan">{{ old('spesifikasi_tujuan') }}</textarea>
                                @error('spesifikasi_tujuan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Ajukan</button>
                            <a href="{{ route('am-renovasi.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
