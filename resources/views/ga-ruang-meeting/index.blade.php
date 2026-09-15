@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Daftar Ruang Meeting</h4>
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
                <h4 class="card-title">Data Ruang Meeting</h4>
                <a href="{{ route('ga-ruang-meeting.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Pesan Ruang
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>No Permintaan</th>
                                <th>Uraian</th>
                                <th>Qty</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($meetings as $index => $meeting)
                            <tr>
                                <td>{{ $meetings->firstItem() + $index }}</td>
                                <td>{{ $meeting->no_permintaan }}</td>
                                <td>{{ Str::limit($meeting->uraian_pemakaian, 30) }}</td>
                                <td>{{ $meeting->qty }}</td>
                                <td>{{ \Carbon\Carbon::parse($meeting->hari_tanggal)->format('d/m/Y') }}</td>
                                <td>{{ $meeting->waktu }}</td>
                                <td>
                                    @php $statusClass = ['draft'=>'secondary','pending'=>'warning','approved'=>'success','rejected'=>'danger','completed'=>'info']; @endphp
                                    <span class="badge badge-{{ $statusClass[$meeting->status] ?? 'secondary' }}">{{ ucfirst($meeting->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('ga-ruang-meeting.show', $meeting) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    @if($meeting->status == 'draft' || $meeting->status == 'rejected')
                                        <a href="{{ route('ga-ruang-meeting.edit', $meeting) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('ga-ruang-meeting.destroy', $meeting) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
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
                {{ $meetings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
