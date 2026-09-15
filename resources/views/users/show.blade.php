@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Detail User</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $user->avatar_url }}" class="rounded-circle" width="120" height="120">
                <h3 class="mt-3">{{ $user->name }}</h3>
                <p class="text-muted">{{ $user->email }}</p>
                <p><span class="badge badge-primary">{{ $user->role }}</span></p>
                <hr>
                <table class="table table-bordered text-left">
                    <tr><th>Perusahaan</th><td>{{ $user->company->name ?? '-' }}</td></tr>
                    <tr><th>Departemen</th><td>{{ $user->department->name ?? '-' }}</td></tr>
                    <tr><th>Bergabung</th><td>{{ $user->created_at->format('d/m/Y H:i') }}</td></tr>
                </table>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection