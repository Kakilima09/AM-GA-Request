@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Daftar Jasa Lembur</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">General Affair</li>
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
                <h4 class="card-title">Data Lembur</h4>
                <a href="{{ route('ga-jasa-lembur.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Ajukan Lembur
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Pelaksanaan</th>
                                <th>Uraian Tugas</th>
                                <th>Hari Kerja</th>
                                <th>Hari Libur</th>
                                <th>SDM</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lemburs as $index => $lembur)
                            <tr>
                                <td>{{ $lemburs->firstItem() + $index }}</td>
                                <td>{{ Str::limit($lembur->pelaksanaan_lembur, 30) }}</td>
                                <td>{{ Str::limit($lembur->uraian_tugas, 30) }}</td>
                                <td>{{ $lembur->hari_kerja }}</td>
                                <td>{{ $lembur->hari_libur }}</td>
                                <td>{{ $lembur->jumlah_sdm }}</td>
                                <td>{{ \Carbon\Carbon::parse($lembur->hari_tanggal)->format('d/m/Y') }}</td>
                                <td>
                                    @php $statusClass = ['draft'=>'secondary','pending'=>'warning','approved'=>'success','rejected'=>'danger','completed'=>'info']; @endphp
                                    <span class="badge badge-{{ $statusClass[$lembur->status] ?? 'secondary' }}">{{ ucfirst($lembur->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('ga-jasa-lembur.show', $lembur) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    @if($lembur->status == 'draft' || $lembur->status == 'rejected')
                                        <a href="{{ route('ga-jasa-lembur.edit', $lembur) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('ga-jasa-lembur.destroy', $lembur) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
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
                {{ $lemburs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
