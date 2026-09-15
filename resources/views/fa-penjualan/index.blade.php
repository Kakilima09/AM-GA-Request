@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Daftar Penjualan FA</h4>
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
                <h4 class="card-title">Data Penjualan</h4>
                <a href="{{ route('fa-penjualan.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Ajukan Penjualan
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
                                <th>Harga Jual</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penjualans as $index => $penjualan)
                            <tr>
                                <td>{{ $penjualans->firstItem() + $index }}</td>
                                <td>{{ $penjualan->no_fa }}</td>
                                <td>{{ $penjualan->nama_fa }}</td>
                                <td>{{ $penjualan->qty }}</td>
                                <td>Rp {{ number_format($penjualan->nbv, 2) }}</td>
                                <td>Rp {{ number_format($penjualan->harga_jual, 2) }}</td>
                                <td><span class="badge badge-{{ $penjualan->jenis == 'internal' ? 'info' : 'danger' }}">{{ ucfirst($penjualan->jenis) }}</span></td>
                                <td>
                                    @php $statusClass = ['draft'=>'secondary','pending'=>'warning','approved'=>'success','rejected'=>'danger','completed'=>'info']; @endphp
                                    <span class="badge badge-{{ $statusClass[$penjualan->status] ?? 'secondary' }}">{{ ucfirst($penjualan->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('fa-penjualan.show', $penjualan) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    @if($penjualan->status == 'draft' || $penjualan->status == 'rejected')
                                        <a href="{{ route('fa-penjualan.edit', $penjualan) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('fa-penjualan.destroy', $penjualan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="text-center">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $penjualans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
