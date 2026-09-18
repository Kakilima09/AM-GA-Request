@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Edit Penghapusan FA</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('fa-penghapusan.index') }}">Fixed Asset</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('fa-penghapusan.update', $faPenghapusan) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label for="email_atasan">Email Atasan (Approval Level 1) <span class="text-danger">*</span></label>
                        <input type="email" name="email_atasan" class="form-control @error('email_atasan') is-invalid @enderror"
                               value="{{ old('email_atasan', $faPenghapusan->email_atasan) }}" placeholder="email atasan">
                        <small class="text-muted">Email atasan yang akan menerima permintaan approval level 1.</small>
                        @error('email_atasan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No FA <span class="text-danger">*</span></label>
                                <input type="text" name="no_fa" class="form-control @error('no_fa') is-invalid @enderror" value="{{ old('no_fa', $faPenghapusan->no_fa) }}" required>
                                @error('no_fa') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama FA <span class="text-danger">*</span></label>
                                <input type="text" name="nama_fa" class="form-control @error('nama_fa') is-invalid @enderror" value="{{ old('nama_fa', $faPenghapusan->nama_fa) }}" required>
                                @error('nama_fa') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Qty <span class="text-danger">*</span></label>
                                <input type="number" name="qty" class="form-control @error('qty') is-invalid @enderror" value="{{ old('qty', $faPenghapusan->qty) }}" min="1" required>
                                @error('qty') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>NBV <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="nbv" class="form-control @error('nbv') is-invalid @enderror" value="{{ old('nbv', $faPenghapusan->nbv) }}" required>
                                @error('nbv') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Foto FA (opsional)</label>
                                @if($faPenghapusan->foto_fa)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $faPenghapusan->foto_fa) }}" alt="Foto FA" style="max-width:100px; border:1px solid #ddd; padding:5px;">
                                    </div>
                                @endif
                                <input type="file" name="foto_fa" class="form-control-file @error('foto_fa') is-invalid @enderror" accept="image/*">
                                @error('foto_fa') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti foto</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Keterangan <span class="text-danger">*</span></label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" required>{{ old('keterangan', $faPenghapusan->keterangan) }}</textarea>
                        @error('keterangan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('fa-penghapusan.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
