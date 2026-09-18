<!-- Top Bar Start -->
<div class="topbar">
    <!-- Navbar -->
    <nav class="navbar-custom">
        <ul class="list-unstyled topbar-nav float-right mb-0">
            <!-- Search -->
            <li class="dropdown hide-phone">
                <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="false" aria-expanded="false">
                    <i data-feather="search" class="topbar-icon"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-lg p-0">
                    <div class="app-search-topbar">
                        <form action="#" method="get">
                            <input type="search" name="search" class="from-control top-search mb-0" placeholder="Cari...">
                            <button type="submit"><i class="ti-search"></i></button>
                        </form>
                    </div>
                </div>
            </li>

            <!-- Notifications (Approval Pending + Database) -->
            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="false" aria-expanded="false">
                    <i data-feather="bell" class="align-self-center topbar-icon"></i>
                    @php
                        $pendingCount = 0;
                        $unreadCount = 0;
                        $totalNotif = 0;
                        if (auth()->check()) {
                            $user = auth()->user();
                            $level = approvalLevelForRole($user->role);
                            if ($level) {
                                $pendingCount = \App\Models\Approval::where('status', 'pending')
                                    ->where('level', $level)
                                    ->where('user_id', $user->id)
                                    ->count();
                            }
                            $unreadCount = $user->unreadNotifications()->count();
                            $totalNotif = $pendingCount + $unreadCount;
                        }
                    @endphp
                    <span class="badge badge-danger badge-pill noti-icon-badge">{{ $totalNotif }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-lg pt-0">
                    <h6 class="dropdown-item-text font-15 m-0 py-3 border-bottom d-flex justify-content-between align-items-center">
                        Notifikasi
                        <span>
                            <span class="badge badge-warning badge-pill">{{ $pendingCount }} approval</span>
                            <span class="badge badge-primary badge-pill">{{ $unreadCount }} baru</span>
                        </span>
                    </h6>
                    <div class="notification-menu" data-simplebar>
                        @if($pendingCount > 0)
                            @php
                                $user = auth()->user();
                                $level = approvalLevelForRole($user->role);
                                $approvals = \App\Models\Approval::with(['approvable', 'user'])
                                    ->where('status', 'pending')
                                    ->when($level, function($q) use ($level, $user) {
                                        return $q->where('level', $level)->where('user_id', $user->id);
                                    })
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
                            @endphp
                            @foreach($approvals as $approval)
                                <a href="{{ route('approval.show', ['type' => class_basename($approval->approvable_type), 'id' => $approval->approvable_id]) }}" class="dropdown-item py-3">
                                    <small class="float-right text-muted pl-2">{{ $approval->created_at->diffForHumans() }}</small>
                                    <div class="media">
                                        <div class="avatar-md bg-soft-warning">
                                            <i data-feather="clock" class="align-self-center icon-xs"></i>
                                        </div>
                                        <div class="media-body align-self-center ml-2 text-truncate">
                                            <h6 class="my-0 font-weight-normal text-dark">Persetujuan {{ str_replace('_', ' ', $approval->level) }}</h6>
                                            <small class="text-muted mb-0">{{ class_basename($approval->approvable_type) }} #{{ $approval->approvable_id }}</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @endif

                        @if($unreadCount > 0)
                            @php
                                $dbNotifications = auth()->user()->unreadNotifications()->latest()->limit(5)->get();
                            @endphp
                            @foreach($dbNotifications as $notification)
                                <a href="{{ route('notifications.open', $notification->id) }}" class="dropdown-item py-3">
                                    <small class="float-right text-muted pl-2">{{ $notification->created_at->diffForHumans() }}</small>
                                    <div class="media">
                                        <div class="avatar-md bg-soft-primary">
                                            <i data-feather="bell" class="align-self-center icon-xs"></i>
                                        </div>
                                        <div class="media-body align-self-center ml-2 text-truncate">
                                            <h6 class="my-0 font-weight-normal text-dark">{{ data_get($notification->data, 'title', 'Notifikasi') }}</h6>
                                            <small class="text-muted mb-0">{{ data_get($notification->data, 'message', '') }}</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @endif

                        @if($pendingCount == 0 && $unreadCount == 0)
                            <a href="#" class="dropdown-item py-3 text-center">Tidak ada notifikasi</a>
                        @endif
                    </div>
                    <!-- All -->
                    <a href="{{ route('notifications.index') }}" class="dropdown-item text-center text-primary">
                        Lihat semua notifikasi <i class="fi-arrow-right"></i>
                    </a>
                </div>
            </li>

            <!-- User Profile -->
            <li class="dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="false" aria-expanded="false">
                    <span class="ml-1 nav-user-name hidden-sm">{{ Auth::user()->name ?? 'Guest' }}</span>
                    <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : asset('assets/images/users/default.png') }}"
                         alt="profile-user" class="rounded-circle" width="36" height="36" />
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i data-feather="user" class="align-self-center icon-xs icon-dual mr-1"></i> Profile
                    </a>
                    <div class="dropdown-divider mb-0"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i data-feather="power" class="align-self-center icon-xs icon-dual mr-1"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                </div>
            </li>
        </ul><!--end topbar-nav-->

        <ul class="list-unstyled topbar-nav mb-0">
            <li>
                <button class="nav-link button-menu-mobile">
                    <i data-feather="menu" class="align-self-center topbar-icon"></i>
                </button>
            </li>
            <li class="creat-btn">
                <div class="nav-link">
                    <a class="btn btn-sm btn-soft-primary" href="{{ route('fa-baru.create') }}" role="button">
                        <i class="fas fa-plus mr-2"></i>Ajukan FA
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <!-- end navbar-->
</div>
<!-- Top Bar End -->