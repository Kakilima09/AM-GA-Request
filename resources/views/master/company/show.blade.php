@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Detail Perusahaan</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Perusahaan</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ $company->name }}</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th style="width:150px;">Kode</th><td>{{ $company->code }}</td></tr>
                    <tr><th>Nama</th><td>{{ $company->name }}</td></tr>
                    <tr><th>Alamat</th><td>{{ $company->address ?? '-' }}</td></tr>
                    <tr><th>Telepon</th><td>{{ $company->phone ?? '-' }}</td></tr>
                    <tr><th>Email</th><td>{{ $company->email ?? '-' }}</td></tr>
                    <tr><th>Dibuat pada</th><td>{{ $company->created_at->format('d/m/Y H:i') }}</td></tr>
                    <tr><th>Terakhir diperbarui</th><td>{{ $company->updated_at->format('d/m/Y H:i') }}</td></tr>
                </table>

                <h5 class="mt-4">Daftar Departemen</h5>
                @if($company->departments->count())
                    <ul>
                        @foreach($company->departments as $dept)
                            <li>{{ $dept->name }} ({{ $dept->code }})</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Belum ada departemen.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('companies.edit', $company) }}" class="btn btn-warning btn-block">Edit Perusahaan</a>
                <a href="{{ route('companies.index') }}" class="btn btn-secondary btn-block mt-2">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
