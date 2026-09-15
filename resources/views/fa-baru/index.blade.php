@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Daftar Pengajuan Fixed Asset</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Fixed Asset</li>
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
                <h4 class="card-title">Data FA Baru</h4>
                <a href="{{ route('fa-baru.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Ajukan FA
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>No. FA</th>
                                <th>Nama FA</th>
                                <th>Qty</th>
                                <th>Estimasi Harga</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Diajukan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($faBarus as $index => $fa)
                            @php
                                $firstItem = $fa->items->first();
                            @endphp
                            <tr>
                                <td>{{ $faBarus->firstItem() + $index }}</td>
                                <td>{{ $firstItem->no_fa ?? '-' }}</td>
                                <td>{{ $firstItem->nama_fa ?? '-' }}</td>
                                <td>{{ $firstItem->qty ?? 0 }}</td>
                                <td>Rp {{ number_format($firstItem->estimasi_harga ?? 0, 2) }}</td>
                                <td>{{ ucfirst($fa->kategori) }}</td>
                                <td>
                                    @php $statusClass = ['draft'=>'secondary','pending'=>'warning','approved'=>'success','rejected'=>'danger','completed'=>'info']; @endphp
                                    <span class="badge badge-{{ $statusClass[$fa->status] ?? 'secondary' }}">
                                        {{ ucfirst($fa->status) }}
                                    </span>
                                </td>
                                <td>{{ $fa->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('fa-baru.show', $fa) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('fa-baru.edit', $fa) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('fa-baru.destroy', $fa) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus pengajuan FA ini beserta semua itemnya?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="text-center">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $faBarus->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
