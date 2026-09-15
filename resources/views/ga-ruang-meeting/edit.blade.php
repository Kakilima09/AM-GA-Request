@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Edit Ruang Meeting</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ga-ruang-meeting.index') }}">General Affair</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('ga-ruang-meeting.update', $gaRuangMeeting) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No Permintaan <span class="text-danger">*</span></label>
                                <input type="text" name="no_permintaan" class="form-control @error('no_permintaan') is-invalid @enderror" value="{{ old('no_permintaan', $gaRuangMeeting->no_permintaan) }}" required>
                                @error('no_permintaan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Qty</label>
                                <input type="number" name="qty" class="form-control @error('qty') is-invalid @enderror" value="{{ old('qty', $gaRuangMeeting->qty) }}" min="1">
                                @error('qty') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Uraian Pemakaian <span class="text-danger">*</span></label>
                        <textarea name="uraian_pemakaian" class="form-control @error('uraian_pemakaian') is-invalid @enderror" rows="3" required>{{ old('uraian_pemakaian', $gaRuangMeeting->uraian_pemakaian) }}</textarea>
                        @error('uraian_pemakaian') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Hari Kerja</label>
                                <input type="number" name="hari_kerja" class="form-control @error('hari_kerja') is-invalid @enderror" value="{{ old('hari_kerja', $gaRuangMeeting->hari_kerja) }}" min="0">
                                @error('hari_kerja') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Hari Libur</label>
                                <input type="number" name="hari_libur" class="form-control @error('hari_libur') is-invalid @enderror" value="{{ old('hari_libur', $gaRuangMeeting->hari_libur) }}" min="0">
                                @error('hari_libur') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jumlah SDM</label>
                                <input type="number" name="jumlah_sdm" class="form-control @error('jumlah_sdm') is-invalid @enderror" value="{{ old('jumlah_sdm', $gaRuangMeeting->jumlah_sdm) }}" min="1">
                                @error('jumlah_sdm') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Hari / Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="hari_tanggal" class="form-control @error('hari_tanggal') is-invalid @enderror" value="{{ old('hari_tanggal', $gaRuangMeeting->hari_tanggal) }}" required>
                                @error('hari_tanggal') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Waktu <span class="text-danger">*</span></label>
                        <input type="time" name="waktu" class="form-control @error('waktu') is-invalid @enderror" value="{{ old('waktu', $gaRuangMeeting->waktu) }}" required>
                        @error('waktu') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" value="{{ old('keterangan', $gaRuangMeeting->keterangan) }}">
                        @error('keterangan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('ga-ruang-meeting.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
