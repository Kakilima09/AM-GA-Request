@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Edit Sewa</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('am-sewa.index') }}">AM Jasa</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('am-sewa.update', $amSewa) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label for="email_atasan">Email Atasan (Approval Level 1) <span class="text-danger">*</span></label>
                        <input type="email" name="email_atasan" class="form-control @error('email_atasan') is-invalid @enderror"
                               value="{{ old('email_atasan', $amSewa->email_atasan) }}" placeholder="email atasan">
                        <small class="text-muted">Email atasan yang akan menerima permintaan approval level 1.</small>
                        @error('email_atasan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3">{{ old('deskripsi', $amSewa->deskripsi) }}</textarea>
                        @error('deskripsi') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lokasi</label>
                                <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                                       value="{{ old('lokasi', $amSewa->lokasi) }}">
                                @error('lokasi') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Durasi</label>
                                <input type="text" name="durasi" class="form-control @error('durasi') is-invalid @enderror"
                                       value="{{ old('durasi', $amSewa->durasi) }}">
                                @error('durasi') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Biaya</label>
                        <input type="number" step="0.01" name="biaya" class="form-control @error('biaya') is-invalid @enderror"
                               value="{{ old('biaya', $amSewa->biaya) }}">
                        @error('biaya') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('am-sewa.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
