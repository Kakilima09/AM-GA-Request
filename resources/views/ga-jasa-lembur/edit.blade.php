@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Edit Jasa Lembur</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ga-jasa-lembur.index') }}">General Affair</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('ga-jasa-lembur.update', $gaJasaLembur) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label for="email_atasan">Email Atasan (Approval Level 1) <span class="text-danger">*</span></label>
                        <input type="email" name="email_atasan" class="form-control @error('email_atasan') is-invalid @enderror"
                               value="{{ old('email_atasan', $gaJasaLembur->email_atasan) }}" placeholder="email atasan">
                        <small class="text-muted">Email atasan yang akan menerima permintaan approval level 1.</small>
                        @error('email_atasan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Pelaksanaan Lembur <span class="text-danger">*</span></label>
                        <input type="text" name="pelaksanaan_lembur" class="form-control @error('pelaksanaan_lembur') is-invalid @enderror" value="{{ old('pelaksanaan_lembur', $gaJasaLembur->pelaksanaan_lembur) }}" required>
                        @error('pelaksanaan_lembur') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Uraian Tugas <span class="text-danger">*</span></label>
                        <textarea name="uraian_tugas" class="form-control @error('uraian_tugas') is-invalid @enderror" rows="3" required>{{ old('uraian_tugas', $gaJasaLembur->uraian_tugas) }}</textarea>
                        @error('uraian_tugas') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Hari Kerja</label>
                                <input type="number" name="hari_kerja" class="form-control @error('hari_kerja') is-invalid @enderror" value="{{ old('hari_kerja', $gaJasaLembur->hari_kerja) }}" min="0">
                                @error('hari_kerja') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Hari Libur</label>
                                <input type="number" name="hari_libur" class="form-control @error('hari_libur') is-invalid @enderror" value="{{ old('hari_libur', $gaJasaLembur->hari_libur) }}" min="0">
                                @error('hari_libur') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jumlah SDM</label>
                                <input type="number" name="jumlah_sdm" class="form-control @error('jumlah_sdm') is-invalid @enderror" value="{{ old('jumlah_sdm', $gaJasaLembur->jumlah_sdm) }}" min="1">
                                @error('jumlah_sdm') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Hari / Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="hari_tanggal" class="form-control @error('hari_tanggal') is-invalid @enderror" value="{{ old('hari_tanggal', $gaJasaLembur->hari_tanggal) }}" required>
                                @error('hari_tanggal') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Waktu <span class="text-danger">*</span></label>
                        <input type="time" name="waktu" class="form-control @error('waktu') is-invalid @enderror" value="{{ old('waktu', $gaJasaLembur->waktu) }}" required>
                        @error('waktu') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('ga-jasa-lembur.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
