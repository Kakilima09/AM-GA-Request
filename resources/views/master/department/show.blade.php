@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Detail Perusahaan</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Departemen</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ $department->name }}</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th style="width:150px;">Kode</th><td>{{ $department->code }}</td></tr>
                    <tr><th>Nama</th><td>{{ $department->name }}</td></tr>
                    <tr><th>Deskripsi</th><td>{{ $department->description ?? '-' }}</td></tr>
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
                <a href="{{ route('departments.edit', $department) }}" class="btn btn-warning btn-block">Edit Departemen</a>
                <a href="{{ route('departments.index') }}" class="btn btn-secondary btn-block mt-2">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
