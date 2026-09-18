@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Edit Mutasi FA</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('fa-mutasi.index') }}">Fixed Asset</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('fa-mutasi.update', $faMutasi) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label for="email_atasan">Email Atasan (Approval Level 1) <span class="text-danger">*</span></label>
                        <input type="email" name="email_atasan" class="form-control @error('email_atasan') is-invalid @enderror"
                               value="{{ old('email_atasan', $faMutasi->email_atasan) }}" placeholder="email atasan">
                        <small class="text-muted">Email atasan yang akan menerima permintaan approval level 1.</small>
                        @error('email_atasan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No FA <span class="text-danger">*</span></label>
                                <input type="text" name="no_fa" class="form-control @error('no_fa') is-invalid @enderror" value="{{ old('no_fa', $faMutasi->no_fa) }}" required>
                                @error('no_fa') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama FA <span class="text-danger">*</span></label>
                                <input type="text" name="nama_fa" class="form-control @error('nama_fa') is-invalid @enderror" value="{{ old('nama_fa', $faMutasi->nama_fa) }}" required>
                                @error('nama_fa') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>User Awal / Departemen <span class="text-danger">*</span></label>
                                <input type="text" name="user_awal_departemen" class="form-control @error('user_awal_departemen') is-invalid @enderror" value="{{ old('user_awal_departemen', $faMutasi->user_awal_departemen) }}" required>
                                @error('user_awal_departemen') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>User Akhir / Departemen <span class="text-danger">*</span></label>
                                <input type="text" name="user_akhir_departemen" class="form-control @error('user_akhir_departemen') is-invalid @enderror" value="{{ old('user_akhir_departemen', $faMutasi->user_akhir_departemen) }}" required>
                                @error('user_akhir_departemen') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Qty <span class="text-danger">*</span></label>
                                <input type="number" name="qty" class="form-control @error('qty') is-invalid @enderror" value="{{ old('qty', $faMutasi->qty) }}" min="1" required>
                                @error('qty') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Keterangan <span class="text-danger">*</span></label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" required>{{ old('keterangan', $faMutasi->keterangan) }}</textarea>
                        @error('keterangan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('fa-mutasi.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
