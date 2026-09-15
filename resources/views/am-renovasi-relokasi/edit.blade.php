@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Edit Renovasi / Relokasi</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('am-renovasi.index') }}">AM Jasa</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('am-renovasi.update', $amRenovasiRelokasi) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lokasi Awal <span class="text-danger">*</span></label>
                                <input type="text" name="lokasi_awal" class="form-control @error('lokasi_awal') is-invalid @enderror"
                                       value="{{ old('lokasi_awal', $amRenovasiRelokasi->lokasi_awal) }}" required>
                                @error('lokasi_awal') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lokasi Tujuan <span class="text-danger">*</span></label>
                                <input type="text" name="lokasi_tujuan" class="form-control @error('lokasi_tujuan') is-invalid @enderror"
                                       value="{{ old('lokasi_tujuan', $amRenovasiRelokasi->lokasi_tujuan) }}" required>
                                @error('lokasi_tujuan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Spesifikasi Awal</label>
                                <textarea name="spesifikasi_awal" class="form-control @error('spesifikasi_awal') is-invalid @enderror" rows="3">{{ old('spesifikasi_awal', $amRenovasiRelokasi->spesifikasi_awal) }}</textarea>
                                @error('spesifikasi_awal') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Spesifikasi Tujuan</label>
                                <textarea name="spesifikasi_tujuan" class="form-control @error('spesifikasi_tujuan') is-invalid @enderror" rows="3">{{ old('spesifikasi_tujuan', $amRenovasiRelokasi->spesifikasi_tujuan) }}</textarea>
                                @error('spesifikasi_tujuan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('am-renovasi.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
