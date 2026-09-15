@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Notifikasi</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Notifikasi</li>
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
                <h4 class="card-title">Semua Notifikasi</h4>
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm" {{ auth()->user()->unreadNotifications->isEmpty() ? 'disabled' : '' }}>
                        <i class="fas fa-check-double"></i> Tandai semua dibaca
                    </button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:50px;">Status</th>
                                <th>Tipe</th>
                                <th>Keterangan</th>
                                <th>Tanggal</th>
                                <th style="width:90px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notifications as $notification)
                            <tr class="{{ $notification->unread() ? 'font-weight-bold' : '' }}">
                                <td class="text-center">
                                    @if($notification->unread())
                                        <span class="badge badge-primary rounded-circle">baru</span>
                                    @else
                                        <span class="badge badge-secondary">dibaca</span>
                                    @endif
                                </td>
                                <td>
                                    @if(data_get($notification->data, 'type') === 'approval_required')
                                        <span class="badge badge-warning">Perlu Persetujuan</span>
                                    @elseif(data_get($notification->data, 'type') === 'approval_processed')
                                        @if(data_get($notification->data, 'status') === 'approved')
                                            <span class="badge badge-success">Disetujui</span>
                                        @else
                                            <span class="badge badge-danger">Ditolak</span>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary">Info</span>
                                    @endif
                                </td>
                                <td>
                                    {{ data_get($notification->data, 'title', 'Notifikasi') }}
                                    <small class="d-block text-muted">{{ data_get($notification->data, 'message', '') }}</small>
                                </td>
                                <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('notifications.open', $notification->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada notifikasi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection