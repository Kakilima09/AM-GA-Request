@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Detail Penghapusan FA</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('fa-penghapusan.index') }}">Fixed Asset</a></li>
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
            <div class="card-header"><h4 class="card-title">Informasi Penghapusan</h4></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>No FA</th><td>{{ $penghapusan->no_fa }}</td></tr>
                    <tr><th>Nama FA</th><td>{{ $penghapusan->nama_fa }}</td></tr>
                    <tr><th>Qty</th><td>{{ $penghapusan->qty }}</td></tr>
                    <tr><th>NBV (Nilai Buku)</th><td>Rp {{ number_format($penghapusan->nbv, 2) }}</td></tr>
                    <tr><th>Keterangan</th><td>{{ $penghapusan->keterangan }}</td></tr>
                    <tr><th>Foto</th>
                        <td>
                            @if($penghapusan->foto_fa)
                                <img src="{{ asset('storage/' . $penghapusan->foto_fa) }}" alt="Foto FA" style="max-width:300px; border:1px solid #ddd; padding:5px;">
                            @else
                                <span class="text-muted">Tidak ada foto</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th>Status</th>
                        <td>
                            @php $statusClass = ['draft'=>'secondary','pending'=>'warning','approved'=>'success','rejected'=>'danger','completed'=>'info']; @endphp
                            <span class="badge badge-{{ $statusClass[$penghapusan->status] ?? 'secondary' }}">{{ ucfirst($penghapusan->status) }}</span>
                        </td>
                    </tr>
                    <tr><th>Diajukan oleh</th><td>{{ $penghapusan->user->name ?? 'N/A' }}</td></tr>
                    <tr><th>Diajukan pada</th><td>{{ $penghapusan->created_at->format('d/m/Y H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Alur Persetujuan</h4></div>
            <div class="card-body">
                <ul class="list-group">
                    @forelse($penghapusan->approvals as $approval)
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
                @if($penghapusan->status == 'pending' && auth()->user()->canApprove($penghapusan))
                    <form action="{{ route('fa-penghapusan.approve', $penghapusan) }}" method="POST" class="mt-3">
                        @csrf @method('PATCH')
                        <input type="hidden" name="level" value="{{ $nextLevel }}">
                        <button type="submit" class="btn btn-success btn-block">Setujui ({{ ucwords(str_replace('_', ' ', $nextLevel)) }})</button>
                    </form>
                    <form action="{{ route('fa-penghapusan.reject', $penghapusan) }}" method="POST" class="mt-2"
                          onsubmit="return confirm('Yakin menolak pengajuan ini?')">
                        @csrf @method('PATCH')
                        <input type="hidden" name="level" value="{{ $nextLevel }}">
                        <button type="submit" class="btn btn-danger btn-block">Tolak Pengajuan</button>
                    </form>
                @endif
                <a href="{{ route('fa-penghapusan.index') }}" class="btn btn-secondary btn-block mt-2">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
