@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Daftar Renovasi / Relokasi</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">AM Jasa</li>
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
                <h4 class="card-title">Data Renovasi / Relokasi</h4>
                <a href="{{ route('am-renovasi.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Ajukan Renovasi
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Lokasi Awal</th>
                                <th>Lokasi Tujuan</th>
                                <th>Status</th>
                                <th>Diajukan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($renovasis as $index => $renovasi)
                            <tr>
                                <td>{{ $renovasis->firstItem() + $index }}</td>
                                <td>{{ $renovasi->lokasi_awal }}</td>
                                <td>{{ $renovasi->lokasi_tujuan }}</td>
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
                                    <span class="badge badge-{{ $statusClass[$renovasi->status] ?? 'secondary' }}">
                                        {{ ucfirst($renovasi->status) }}
                                    </span>
                                </td>
                                <td>{{ $renovasi->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('am-renovasi.show', $renovasi) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($renovasi->status == 'draft' || $renovasi->status == 'rejected')
                                        <a href="{{ route('am-renovasi.edit', $renovasi) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('am-renovasi.destroy', $renovasi) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $renovasis->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
