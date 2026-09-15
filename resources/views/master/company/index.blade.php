@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Daftar Perusahaan</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Master Data</li>
            </ol>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $errors->first() }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Data Perusahaan</h4>
                <a href="{{ route('companies.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Perusahaan
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Perusahaan</th>
                                <th>Telepon</th>
                                <th>Email</th>
                                <th>Jumlah Departemen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($companies as $index => $company)
                            <tr>
                                <td>{{ $companies->firstItem() + $index }}</td>
                                <td><span class="badge badge-info">{{ $company->code }}</span></td>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->departments_count ?? $company->departments->count() }}</td>
                                <td>
                                    <a href="{{ route('companies.show', $company) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('companies.edit', $company) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('companies.destroy', $company) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus perusahaan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data perusahaan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $companies->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
