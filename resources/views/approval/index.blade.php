@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Daftar Persetujuan</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Persetujuan</li>
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

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Menunggu Persetujuan Anda</h4>
                <span class="badge badge-warning badge-pill">{{ $approvals->total() }} pending</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Modul</th>
                                <th>Keterangan</th>
                                <th>Level Persetujuan</th>
                                <th>Diajukan Oleh</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($approvals as $index => $approval)
                            <tr>
                                <td>{{ $approvals->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge badge-primary">{{ class_basename($approval->approvable_type) }}</span>
                                </td>
                                <td>
                                    {{ $approval->approvable->nama_fa ?? $approval->approvable->nama_barang ?? $approval->approvable->no_polisi ?? $approval->approvable->deskripsi ?? '#' . $approval->approvable_id }}
                                </td>
                                <td>{{ ucwords(str_replace('_', ' ', $approval->level)) }}</td>
                                <td>{{ $approval->user->name ?? '-' }}</td>
                                <td>{{ $approval->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('approval.show', ['type' => class_basename($approval->approvable_type), 'id' => $approval->approvable_id]) }}"
                                       class="btn btn-sm btn-primary"><i class="fas fa-eye"></i> Proses</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada pengajuan yang menunggu persetujuan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $approvals->links() }}
            </div>
        </div>
    </div>
</div>
@endsection