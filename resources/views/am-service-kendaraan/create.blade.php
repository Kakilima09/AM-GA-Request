@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Service Kendaraan</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('am-service-kendaraan.index') }}">AM Jasa</a></li>
                <li class="breadcrumb-item active">Service Kendaraan</li>
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
                <h4 class="card-title">Form Pengajuan Service Kendaraan</h4>
                <p class="text-muted mb-0">Isi data kendaraan dan pilih jenis perbaikan yang diperlukan.</p>
            </div>
            <div class="card-body">
                <form action="{{ route('am-service-kendaraan.store') }}" method="POST" enctype="multipart/form-data">
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
                                <label for="no_polisi">No. Polisi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('no_polisi') is-invalid @enderror"
                                       id="no_polisi" name="no_polisi" value="{{ old('no_polisi') }}" placeholder="Contoh: B 1234 ABC">
                                @error('no_polisi')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="merk_type">Merk / Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('merk_type') is-invalid @enderror"
                                       id="merk_type" name="merk_type" value="{{ old('merk_type') }}" placeholder="Contoh: Toyota Avanza">
                                @error('merk_type')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto_km">Foto KM / Odometer <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('foto_km') is-invalid @enderror"
                                       id="foto_km" name="foto_km" accept="image/*" required>
                                <small class="text-muted">Upload foto speedometer/odometer kendaraan</small>
                                @error('foto_km')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mt-2">
                                <img id="previewFotoKm" src="#" alt="Preview Foto KM" style="max-width:200px; display:none;" class="img-thumbnail">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="keluhan">Keluhan (opsional)</label>
                                <textarea class="form-control @error('keluhan') is-invalid @enderror"
                                          id="keluhan" name="keluhan" rows="2" placeholder="Deskripsi keluhan...">{{ old('keluhan') }}</textarea>
                                @error('keluhan')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3">Pilih Perbaikan / Penggantian</h5>

                    <div class="row">
                        @php
                            $checkboxes = [
                                'oli' => 'Oli',
                                'tune_up' => 'Tune Up',
                                'rem' => 'Rem',
                                'ac' => 'AC',
                                'kopling' => 'Kopling',
                                'lampu' => 'Lampu',
                                'accu' => 'Accu',
                                'filter' => 'Filter',
                                'balancing' => 'Balancing',
                                'spooring' => 'Spooring',
                                'ban' => 'Ban',
                                'wiper' => 'Wiper',
                                'overhaul' => 'Overhaul',
                            ];
                        @endphp
                        @foreach ($checkboxes as $key => $label)
                            <div class="col-md-3 col-sm-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input @error($key) is-invalid @enderror"
                                           id="{{ $key }}" name="{{ $key }}" value="1" {{ old($key) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="{{ $key }}">{{ $label }}</label>
                                    @error($key)
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="lain_lain">Lain-lain (opsional)</label>
                                <input type="text" class="form-control @error('lain_lain') is-invalid @enderror"
                                       id="lain_lain" name="lain_lain" value="{{ old('lain_lain') }}" placeholder="Jika ada perbaikan lain, tulis di sini">
                                @error('lain_lain')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Ajukan Service</button>
                            <a href="{{ route('am-service-kendaraan.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div><!-- end row -->

@push('scripts')
<script>
    document.getElementById('foto_km').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('previewFotoKm');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endpush
@endsection
