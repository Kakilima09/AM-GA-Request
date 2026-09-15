@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Daftar Mutasi FA</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Fixed Asset</li>
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
                <h4 class="card-title">Data Mutasi</h4>
                <a href="{{ route('fa-mutasi.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Ajukan Mutasi
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>No FA</th>
                                <th>Nama FA</th>
                                <th>User Awal</th>
                                <th>User Akhir</th>
                                <th>Qty</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mutasis as $index => $mutasi)
                            <tr>
                                <td>{{ $mutasis->firstItem() + $index }}</td>
                                <td>{{ $mutasi->no_fa }}</td>
                                <td>{{ $mutasi->nama_fa }}</td>
                                <td>{{ $mutasi->user_awal_departemen }}</td>
                                <td>{{ $mutasi->user_akhir_departemen }}</td>
                                <td>{{ $mutasi->qty }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'draft' => 'secondary',
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'completed' => 'info'
                                        ];
                                    @endphp
                                    <span class="badge badge-{{ $statusClass[$mutasi->status] ?? 'secondary' }}">
                                        {{ ucfirst($mutasi->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('fa-mutasi.show', $mutasi) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    @if($mutasi->status == 'draft' || $mutasi->status == 'rejected')
                                        <a href="{{ route('fa-mutasi.edit', $mutasi) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('fa-mutasi.destroy', $mutasi) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $mutasis->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
