@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Daftar Sewa</h4>
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
                <h4 class="card-title">Data Sewa</h4>
                <a href="{{ route('am-sewa.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Ajukan Sewa
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Deskripsi</th>
                                <th>Lokasi</th>
                                <th>Biaya</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sewas as $index => $sewa)
                            <tr>
                                <td>{{ $sewas->firstItem() + $index }}</td>
                                <td>{{ Str::limit($sewa->deskripsi, 30) }}</td>
                                <td>{{ $sewa->lokasi }}</td>
                                <td>Rp {{ number_format($sewa->biaya, 2) }}</td>
                                <td>
                                    @php $statusClass = ['draft'=>'secondary','pending'=>'warning','approved'=>'success','rejected'=>'danger','completed'=>'info']; @endphp
                                    <span class="badge badge-{{ $statusClass[$sewa->status] ?? 'secondary' }}">{{ ucfirst($sewa->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('am-sewa.show', $sewa) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    @if($sewa->status == 'draft' || $sewa->status == 'rejected')
                                        <a href="{{ route('am-sewa.edit', $sewa) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('am-sewa.destroy', $sewa) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
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
                {{ $sewas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
