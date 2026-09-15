@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Daftar Penghapusan FA</h4>
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
                <h4 class="card-title">Data Penghapusan</h4>
                <a href="{{ route('fa-penghapusan.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Ajukan Penghapusan
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
                                <th>Qty</th>
                                <th>NBV</th>
                                <th>Foto</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penghapusans as $index => $penghapusan)
                            <tr>
                                <td>{{ $penghapusans->firstItem() + $index }}</td>
                                <td>{{ $penghapusan->no_fa }}</td>
                                <td>{{ $penghapusan->nama_fa }}</td>
                                <td>{{ $penghapusan->qty }}</td>
                                <td>Rp {{ number_format($penghapusan->nbv, 2) }}</td>
                                <td>
                                    @if($penghapusan->foto_fa)
                                        <img src="{{ asset('storage/' . $penghapusan->foto_fa) }}" alt="Foto FA" style="width:50px; height:50px; object-fit:cover; border-radius:4px;">
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>
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
                                    <span class="badge badge-{{ $statusClass[$penghapusan->status] ?? 'secondary' }}">
                                        {{ ucfirst($penghapusan->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('fa-penghapusan.show', $penghapusan) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    @if($penghapusan->status == 'draft' || $penghapusan->status == 'rejected')
                                        <a href="{{ route('fa-penghapusan.edit', $penghapusan) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('fa-penghapusan.destroy', $penghapusan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
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
                {{ $penghapusans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
