@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Daftar BAST</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">BAST</li>
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

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Data BAST</h4>
                <a href="{{ route('bast.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Buat BAST
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Pemohon</th>
                                <th>Perusahaan</th>
                                <th>Departemen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($basts as $index => $bast)
                            <tr>
                                <td>{{ $basts->firstItem() + $index }}</td>
                                <td>{{ \Carbon\Carbon::parse($bast->tanggal)->format('d/m/Y') }}</td>
                                <td><span class="badge badge-{{ $bast->tipe == 'fa' ? 'primary' : 'info' }}">{{ strtoupper($bast->tipe) }}</span></td>
                                <td>{{ $bast->nama_pemohon }}</td>
                                <td>{{ $bast->company->name ?? '-' }}</td>
                                <td>{{ $bast->department->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('bast.show', $bast) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('bast.edit', $bast) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('bast.export-pdf', $bast) }}" class="btn btn-sm btn-danger" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                    <form action="{{ route('bast.destroy', $bast) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus BAST ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center">Tidak ada data BAST.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $basts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
