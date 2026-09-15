@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Edit User</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password (kosongkan jika tidak diubah)</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                                    <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="admin_am" {{ old('role', $user->role) == 'admin_am' ? 'selected' : '' }}>Admin AM</option>
                                    <option value="admin_ga" {{ old('role', $user->role) == 'admin_ga' ? 'selected' : '' }}>Admin GA</option>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="atasan_l1" {{ old('role', $user->role) == 'atasan_l1' ? 'selected' : '' }}>Atasan Level 1</option>
                                    <option value="atasan_l2" {{ old('role', $user->role) == 'atasan_l2' ? 'selected' : '' }}>Atasan Level 2</option>
                                    <option value="company_head" {{ old('role', $user->role) == 'company_head' ? 'selected' : '' }}>Company Head</option>
                                    <option value="ceo" {{ old('role', $user->role) == 'ceo' ? 'selected' : '' }}>CEO</option>
                                    <option value="it_staff" {{ old('role', $user->role) == 'it_staff' ? 'selected' : '' }}>IT Staff</option>
                                    <option value="manager_am" {{ old('role', $user->role) == 'manager_am' ? 'selected' : '' }}>Manager AM</option>
                                    <option value="manager_ga" {{ old('role', $user->role) == 'manager_ga' ? 'selected' : '' }}>Manager GA</option>
                                </select>
                                @error('role') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Perusahaan</label>
                                <select name="company_id" class="form-control">
                                    <option value="">Pilih Perusahaan</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id', $user->company_id) == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Departemen</label>
                                <select name="department_id" class="form-control">
                                    <option value="">Pilih Departemen</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Avatar</label>
                                <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar_url }}" width="60" class="mt-2 rounded-circle">
                                @endif
                                @error('avatar') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection