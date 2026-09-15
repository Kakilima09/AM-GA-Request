@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Daftar Departemen</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Master Data</li>
            </ol>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Data Departemen</h4>
                <a href="{{ route('departments.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Departemen
                </a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Departemen</th>
                            <th>Perusahaan</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $index => $dept)
                        <tr>
                            <td>{{ $departments->firstItem() + $index }}</td>
                            <td><span class="badge badge-info">{{ $dept->code }}</span></td>
                            <td>{{ $dept->name }}</td>
                            <td>{{ $dept->company->name ?? '-' }}</td>
                            <td>{{ Str::limit($dept->description, 30) }}</td>
                            <td>
                                <a href="{{ route('departments.show', $dept) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('departments.edit', $dept) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('departments.destroy', $dept) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $departments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
