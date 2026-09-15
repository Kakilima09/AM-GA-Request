@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Detail Ruang Meeting</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ga-ruang-meeting.index') }}">General Affair</a></li>
                <li class="breadcrumb-item active">Detail</li>
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
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Informasi Pemesanan</h4></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>No Permintaan</th><td>{{ $meeting->no_permintaan }}</td></tr>
                    <tr><th>Uraian Pemakaian</th><td>{{ $meeting->uraian_pemakaian }}</td></tr>
                    <tr><th>Qty</th><td>{{ $meeting->qty }}</td></tr>
                    <tr><th>Hari Kerja</th><td>{{ $meeting->hari_kerja }}</td></tr>
                    <tr><th>Hari Libur</th><td>{{ $meeting->hari_libur }}</td></tr>
                    <tr><th>Jumlah SDM</th><td>{{ $meeting->jumlah_sdm }}</td></tr>
                    <tr><th>Hari / Tanggal</th><td>{{ \Carbon\Carbon::parse($meeting->hari_tanggal)->format('d/m/Y') }}</td></tr>
                    <tr><th>Waktu</th><td>{{ $meeting->waktu }}</td></tr>
                    <tr><th>Keterangan</th><td>{{ $meeting->keterangan ?? '-' }}</td></tr>
                    <tr><th>Status</th>
                        <td>
                            @php $statusClass = ['draft'=>'secondary','pending'=>'warning','approved'=>'success','rejected'=>'danger','completed'=>'info']; @endphp
                            <span class="badge badge-{{ $statusClass[$meeting->status] ?? 'secondary' }}">{{ ucfirst($meeting->status) }}</span>
                        </td>
                    </tr>
                    <tr><th>Diajukan oleh</th><td>{{ $meeting->user->name ?? 'N/A' }}</td></tr>
                    <tr><th>Diajukan pada</th><td>{{ $meeting->created_at->format('d/m/Y H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Alur Persetujuan</h4></div>
            <div class="card-body">
                <ul class="list-group">
                    @forelse($meeting->approvals as $approval)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ ucwords(str_replace('_', ' ', $approval->level)) }}
                            <span class="badge badge-{{ $approval->status == 'approved' ? 'success' : ($approval->status == 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($approval->status) }}
                            </span>
                            @if($approval->user)<small>{{ $approval->user->name }}</small>@endif
                        </li>
                    @empty
                        <li class="list-group-item">Belum ada approval</li>
                    @endforelse
                </ul>
                @if($meeting->status == 'pending' && auth()->user()->canApprove($meeting))
                    <form action="{{ route('ga-ruang-meeting.approve', $meeting) }}" method="POST" class="mt-3">
                        @csrf @method('PATCH')
                        <input type="hidden" name="level" value="{{ $nextLevel }}">
                        <button type="submit" class="btn btn-success btn-block">Setujui ({{ ucwords(str_replace('_', ' ', $nextLevel)) }})</button>
                    </form>
                    <form action="{{ route('ga-ruang-meeting.reject', $meeting) }}" method="POST" class="mt-2"
                          onsubmit="return confirm('Yakin menolak pengajuan ini?')">
                        @csrf @method('PATCH')
                        <input type="hidden" name="level" value="{{ $nextLevel }}">
                        <button type="submit" class="btn btn-danger btn-block">Tolak Pengajuan</button>
                    </form>
                @endif
                <a href="{{ route('ga-ruang-meeting.index') }}" class="btn btn-secondary btn-block mt-2">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
