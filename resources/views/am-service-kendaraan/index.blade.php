@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Daftar Service Kendaraan</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">AM Jasa</li>
            </ol>
        </div>
    </div>
</div>

@if (session('success'))
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
                <h4 class="card-title">Data Service Kendaraan</h4>
                <a href="{{ route('am-service-kendaraan.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Ajukan Service
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>No. Polisi</th>
                                <th>Merk / Type</th>
                                <th>KM</th>
                                <th>Keluhan</th>
                                <th>Status</th>
                                <th>Diajukan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($services as $index => $service)
                            <tr>
                                <td>{{ $services->firstItem() + $index }}</td>
                                <td>{{ $service->no_polisi }}</td>
                                <td>{{ $service->merk_type }}</td>
                                <td>{{ number_format($service->km) }}</td>
                                @if($service->foto_km)
                                    <tr><th>Foto KM</th><td><img src="{{ asset('storage/'.$service->foto_km) }}" style="max-width:200px;"></td></tr>
                                @endif
                                <td>{{ Str::limit($service->keluhan, 30) }}</td>
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
                                    <span class="badge badge-{{ $statusClass[$service->status] ?? 'secondary' }}">
                                        {{ ucfirst($service->status) }}
                                    </span>
                                </td>
                                <td>{{ $service->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('am-service-kendaraan.show', $service) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if ($service->status == 'draft' || $service->status == 'rejected')
                                        <a href="{{ route('am-service-kendaraan.edit', $service) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('am-service-kendaraan.destroy', $service) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $services->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
