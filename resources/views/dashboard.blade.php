@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Dashboard</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
</div>

{{-- ============ SUPER ADMIN ============ --}}
@if($role === 'super_admin')
<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col">
                        <p class="text-dark mb-1 font-weight-semibold">Total User</p>
                        <h3 class="my-0">{{ number_format($totalUsers) }}</h3>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="report-main-icon bg-light-alt">
                            <i data-feather="users" class="align-self-center text-muted icon-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col">
                        <p class="text-dark mb-1 font-weight-semibold">Total Modul</p>
                        <h3 class="my-0">{{ count($moduleList) }}</h3>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="report-main-icon bg-light-alt">
                            <i data-feather="grid" class="align-self-center text-muted icon-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col">
                        <p class="text-dark mb-1 font-weight-semibold">Total Pengajuan</p>
                        <h3 class="my-0">{{ number_format($stats['total']) }}</h3>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="report-main-icon bg-light-alt">
                            <i data-feather="file-text" class="align-self-center text-muted icon-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col">
                        <p class="text-dark mb-1 font-weight-semibold">Pending Approval</p>
                        <h3 class="my-0 text-warning">{{ number_format($stats['pending']) }}</h3>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="report-main-icon bg-light-alt">
                            <i data-feather="clock" class="align-self-center text-warning icon-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ============ ADMIN AM ============ --}}
@if(in_array($role, ['admin_am', 'super_admin']))
<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col">
                        <p class="text-dark mb-1 font-weight-semibold">Total AM Jasa</p>
                        <h3 class="my-0">{{ number_format($amStats['total'] ?? 0) }}</h3>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="report-main-icon bg-light-alt">
                            <i data-feather="tool" class="align-self-center text-muted icon-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col">
                        <p class="text-dark mb-1 font-weight-semibold">AM Pending</p>
                        <h3 class="my-0 text-warning">{{ number_format($amStats['pending'] ?? 0) }}</h3>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="report-main-icon bg-light-alt">
                            <i data-feather="clock" class="align-self-center text-warning icon-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col">
                        <p class="text-dark mb-1 font-weight-semibold">AM Selesai</p>
                        <h3 class="my-0 text-success">{{ number_format($amStats['completed'] ?? 0) }}</h3>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="report-main-icon bg-light-alt">
                            <i data-feather="check-circle" class="align-self-center text-success icon-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col">
                        <p class="text-dark mb-1 font-weight-semibold">Total FA</p>
                        @php
                            $faTotal = ($stats['modules']['fa-baru']['total'] ?? 0) + 
                                       ($stats['modules']['fa-penghapusan']['total'] ?? 0) + 
                                       ($stats['modules']['fa-penjualan']['total'] ?? 0) + 
                                       ($stats['modules']['fa-mutasi']['total'] ?? 0);
                        @endphp
                        <h3 class="my-0">{{ number_format($faTotal) }}</h3>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="report-main-icon bg-light-alt">
                            <i data-feather="archive" class="align-self-center text-muted icon-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ============ ADMIN GA ============ --}}
@if(in_array($role, ['admin_ga', 'super_admin']))
<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col">
                        <p class="text-dark mb-1 font-weight-semibold">Total GA</p>
                        <h3 class="my-0">{{ number_format($gaStats['total'] ?? 0) }}</h3>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="report-main-icon bg-light-alt">
                            <i data-feather="briefcase" class="align-self-center text-muted icon-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col">
                        <p class="text-dark mb-1 font-weight-semibold">GA Pending</p>
                        <h3 class="my-0 text-warning">{{ number_format($gaStats['pending'] ?? 0) }}</h3>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="report-main-icon bg-light-alt">
                            <i data-feather="clock" class="align-self-center text-warning icon-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col">
                        <p class="text-dark mb-1 font-weight-semibold">GA Selesai</p>
                        <h3 class="my-0 text-success">{{ number_format($gaStats['completed'] ?? 0) }}</h3>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="report-main-icon bg-light-alt">
                            <i data-feather="check-circle" class="align-self-center text-success icon-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col">
                        <p class="text-dark mb-1 font-weight-semibold">Total BAST</p>
                        <h3 class="my-0">{{ number_format($stats['modules']['bast']['total'] ?? 0) }}</h3>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="report-main-icon bg-light-alt">
                            <i data-feather="file-text" class="align-self-center text-muted icon-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ============ USER BIASA ============ --}}
@if($role === 'user')
<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col">
                        <p class="text-dark mb-1 font-weight-semibold">Total Pengajuan</p>
                        <h3 class="my-0">{{ number_format($userStats['total'] ?? 0) }}</h3>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="report-main-icon bg-light-alt">
                            <i data-feather="file-text" class="align-self-center text-muted icon-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col">
                        <p class="text-dark mb-1 font-weight-semibold">Menunggu Persetujuan</p>
                        <h3 class="my-0 text-warning">{{ number_format($userStats['pending'] ?? 0) }}</h3>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="report-main-icon bg-light-alt">
                            <i data-feather="clock" class="align-self-center text-warning icon-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col">
                        <p class="text-dark mb-1 font-weight-semibold">Disetujui</p>
                        <h3 class="my-0 text-success">{{ number_format($userStats['approved'] ?? 0) }}</h3>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="report-main-icon bg-light-alt">
                            <i data-feather="check-circle" class="align-self-center text-success icon-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col">
                        <p class="text-dark mb-1 font-weight-semibold">Ditolak</p>
                        <h3 class="my-0 text-danger">{{ number_format($userStats['rejected'] ?? 0) }}</h3>
                    </div>
                    <div class="col-auto align-self-center">
                        <div class="report-main-icon bg-light-alt">
                            <i data-feather="x-circle" class="align-self-center text-danger icon-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ============ CHART (untuk semua role) ============ --}}
@if(count($chartData['labels']) > 0)
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Statistik Pengajuan per Modul</h4>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height:300px;">
                    <canvas id="moduleChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Aktivitas Terbaru</h4>
            </div>
            <div class="card-body">
                <div class="help-activity-height" data-simplebar style="height: 300px;">
                    @if(count($recentActivities) > 0)
                        @foreach($recentActivities as $activity)
                        <div class="activity-info">
                            <div class="icon-info-activity">
                                <i data-feather="{{ $activity['status'] == 'pending' ? 'clock' : ($activity['status'] == 'approved' ? 'check-circle' : 'x-circle') }}" 
                                   class="bg-soft-{{ $activity['status'] == 'pending' ? 'warning' : ($activity['status'] == 'approved' ? 'success' : 'danger') }}"></i>
                            </div>
                            <div class="activity-info-text">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="text-muted mb-0 font-13 w-75">
                                        <span>{{ $activity['user'] }}</span> 
                                        mengajukan <strong>{{ $activity['module'] }}</strong>
                                        <br>
                                        <small>{{ Str::limit($activity['title'], 40) }}</small>
                                    </p>
                                    <small class="text-muted">{{ $activity['created_at']->diffForHumans() }}</small>
                                </div>
                                <span class="badge badge-{{ $activity['status'] == 'pending' ? 'warning' : ($activity['status'] == 'approved' ? 'success' : 'danger') }} badge-sm mt-1">
                                    {{ ucfirst($activity['status']) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-center text-muted py-5">Belum ada aktivitas.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ============ TABLE PER MODUL (Super Admin) ============ --}}
@if($role === 'super_admin' && count($stats['modules']) > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Rincian per Modul</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Modul</th>
                                <th>Total</th>
                                <th>Draft</th>
                                <th>Pending</th>
                                <th>Approved</th>
                                <th>Rejected</th>
                                <th>Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['modules'] as $moduleName => $data)
                            <tr>
                                <td><strong>{{ ucwords(str_replace('-', ' ', $moduleName)) }}</strong></td>
                                <td>{{ $data['total'] }}</td>
                                <td><span class="badge badge-secondary">{{ $data['draft'] }}</span></td>
                                <td><span class="badge badge-warning">{{ $data['pending'] }}</span></td>
                                <td><span class="badge badge-success">{{ $data['approved'] }}</span></td>
                                <td><span class="badge badge-danger">{{ $data['rejected'] }}</span></td>
                                <td><span class="badge badge-info">{{ $data['completed'] }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ============ DAFTAR MODUL (Super Admin) ============ --}}
@if($role === 'super_admin' && count($moduleList) > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Modul Sistem</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($moduleList as $module)
                    <div class="col-md-3 col-lg-2">
                        <div class="badge badge-soft-primary p-3 m-1 d-block text-center">
                            {{ ucwords(str_replace('-', ' ', $module)) }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
}
.badge-sm {
    font-size: 10px;
    padding: 3px 8px;
}
.activity-info {
    display: flex;
    padding: 10px 0;
    border-bottom: 1px solid #f1f3f7;
}
.activity-info:last-child {
    border-bottom: none;
}
.icon-info-activity {
    margin-right: 12px;
    flex-shrink: 0;
}
.icon-info-activity i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    font-size: 16px;
    color: #fff;
}
.bg-soft-warning { background: #ffc107; }
.bg-soft-success { background: #28a745; }
.bg-soft-danger { background: #dc3545; }
.bg-soft-info { background: #17a2b8; }
.bg-soft-primary { background: #007bff; }
.report-main-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 10px;
}
.help-activity-height {
    max-height: 300px;
    overflow-y: auto;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('moduleChart');
    if (ctx) {
        var chartData = @json($chartData);
        var colors = {
            pending: '#ffc107',
            approved: '#28a745',
            rejected: '#dc3545',
        };

        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Pending',
                        data: chartData.pending,
                        backgroundColor: colors.pending,
                        borderRadius: 4,
                    },
                    {
                        label: 'Approved',
                        data: chartData.approved,
                        backgroundColor: colors.approved,
                        borderRadius: 4,
                    },
                    {
                        label: 'Rejected',
                        data: chartData.rejected,
                        backgroundColor: colors.rejected,
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }
});
</script>
@endpush