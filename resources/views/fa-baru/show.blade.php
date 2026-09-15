@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Detail Pengajuan FA</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('fa-baru.index') }}">Fixed Asset</a></li>
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
            <div class="card-header">
                <h4 class="card-title">Informasi FA</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>No. FA</th><td>{{ $faBaru->no_fa ?? '-' }}</td></tr>
                    <tr><th>Nama FA</th><td>{{ $faBaru->nama_fa ?? '-' }}</td></tr>
                    <tr><th>Kategori</th><td>{{ ucfirst($faBaru->kategori) }}</td></tr>
                    @if($faBaru->kategori == 'kendaraan')
                        <tr><th>Tipe Kendaraan</th><td>{{ $faBaru->tipe_kendaraan ?? '-' }}</td></tr>
                        <tr><th>COP</th><td>{{ $faBaru->is_cop ? 'Ya' : 'Tidak' }}</td></tr>
                    @endif
                    <tr><th>Status</th>
                        <td>
                            @php $statusClass = ['draft'=>'secondary','pending'=>'warning','approved'=>'success','rejected'=>'danger','completed'=>'info']; @endphp
                            <span class="badge badge-{{ $statusClass[$faBaru->status] ?? 'secondary' }}">{{ ucfirst($faBaru->status) }}</span>
                        </td>
                    </tr>
                    <tr><th>Diajukan oleh</th><td>{{ $faBaru->user->name ?? 'N/A' }}</td></tr>
                    <tr><th>Diajukan pada</th><td>{{ $faBaru->created_at->format('d/m/Y H:i') }}</td></tr>
                </table>

                @if($faBaru->items->count() > 0)
                <h5 class="mt-4">Detail Item</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No FA</th>
                            <th>Nama FA</th>
                            <th>Spesifikasi</th>
                            <th>Qty</th>
                            <th>Estimasi Harga</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($faBaru->items as $item)
                        <tr>
                            <td>{{ $item->no_fa }}</td>
                            <td>{{ $item->nama_fa }}</td>
                            <td>{{ $item->spesifikasi ?? '-' }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>Rp {{ number_format($item->estimasi_harga, 2) }}</td>
                            <td>Rp {{ number_format($item->qty * $item->estimasi_harga, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-right">Total Estimasi</th>
                            <th>Rp {{ number_format($totalEstimasi ?? $faBaru->items->sum('estimasi_harga'), 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Alur Persetujuan</h4>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @forelse($faBaru->approvals as $approval)
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

                @if($faBaru->status == 'pending' && auth()->user()->canApprove($faBaru))
                    <form action="{{ route('fa-baru.approve', $faBaru) }}" method="POST" class="mt-3">
                        @csrf @method('PATCH')
                        <input type="hidden" name="level" value="{{ $nextLevel }}">
                        <button type="submit" class="btn btn-success btn-block">Setujui ({{ ucwords(str_replace('_', ' ', $nextLevel)) }})</button>
                    </form>
                    <form action="{{ route('fa-baru.reject', $faBaru) }}" method="POST" class="mt-2"
                          onsubmit="return confirm('Yakin menolak pengajuan ini?')">
                        @csrf @method('PATCH')
                        <input type="hidden" name="level" value="{{ $nextLevel }}">
                        <button type="submit" class="btn btn-danger btn-block">Tolak Pengajuan</button>
                    </form>
                @endif

                <a href="{{ route('fa-baru.index') }}" class="btn btn-secondary btn-block mt-2">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
