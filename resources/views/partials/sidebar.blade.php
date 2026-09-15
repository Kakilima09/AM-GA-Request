<!-- Left Sidenav -->
<div class="left-sidenav">
    <!-- LOGO -->
    <div class="brand">
        <a href="{{ route('dashboard') }}" class="logo">
            <span>
                <img src="{{ asset('assets/images/logo.sm3.png') }}" alt="logo-small" class="logo-sm">
            </span>
            {{-- <span>
                <img src="{{ asset('assets/images/logo.png') }}" alt="logo-large" class="logo-lg logo-light">
                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo-large" class="logo-lg logo-dark">
            </span> --}}
        </a>
    </div>
    <!--end logo-->
    <div class="menu-content h-100" data-simplebar>
        <ul class="metismenu left-sidenav-menu">
            <li class="menu-label mt-0">Main</li>
            <li>
                <a href="{{ route('dashboard') }}">
                    <i data-feather="home" class="align-self-center menu-icon"></i><span>Dashboard</span>
                </a>
            </li>

            @php
                $user = auth()->user();
                $role = $user->role ?? 'user';

                if (!function_exists('hasModuleAccess')) {
                    function hasModuleAccess($moduleName) {
                        $user = auth()->user();
                        if (!$user) return false;
                        if ($user->role === 'super_admin') return true;

                        // Untuk user biasa, semua modul aktif kecuali BAST
                        if ($user->role === 'user') {
                            return $moduleName !== 'bast';
                        }

                        // Untuk admin, cek dari tabel module_access
                        $access = \App\Models\ModuleAccess::where('role', $user->role)
                                    ->where('module_name', $moduleName)
                                    ->first();
                        return $access && $access->is_active;
                    }
                }
            @endphp

            <!-- AM Jasa -->
            @if(hasModuleAccess('am-service-kendaraan') || hasModuleAccess('am-service-fa-non') || hasModuleAccess('am-sewa') || hasModuleAccess('am-renovasi'))
            <li>
                <a href="javascript: void(0);">
                    <i data-feather="tool" class="align-self-center menu-icon"></i><span>AM Jasa</span>
                    <span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    @if(hasModuleAccess('am-service-kendaraan'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('am-service-kendaraan.index') }}">
                            <i class="ti-control-record"></i>Service Kendaraan
                        </a>
                    </li>
                    @endif
                    @if(hasModuleAccess('am-service-fa-non'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('am-service-fa-non-kendaraan.index') }}">
                            <i class="ti-control-record"></i>Service FA Non
                        </a>
                    </li>
                    @endif
                    @if(hasModuleAccess('am-sewa'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('am-sewa.index') }}">
                            <i class="ti-control-record"></i>Sewa
                        </a>
                    </li>
                    @endif
                    @if(hasModuleAccess('am-renovasi'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('am-renovasi.index') }}">
                            <i class="ti-control-record"></i>Renovasi / Relokasi
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            <!-- Fixed Asset -->
            @if(hasModuleAccess('fa-baru') || hasModuleAccess('fa-penghapusan') || hasModuleAccess('fa-penjualan') || hasModuleAccess('fa-mutasi'))
            <li>
                <a href="javascript: void(0);">
                    <i data-feather="archive" class="align-self-center menu-icon"></i><span>Fixed Asset</span>
                    <span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    @if(hasModuleAccess('fa-baru'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('fa-baru.index') }}">
                            <i class="ti-control-record"></i>Baru / Penambahan
                        </a>
                    </li>
                    @endif
                    @if(hasModuleAccess('fa-penghapusan'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('fa-penghapusan.index') }}">
                            <i class="ti-control-record"></i>Penghapusan
                        </a>
                    </li>
                    @endif
                    @if(hasModuleAccess('fa-penjualan'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('fa-penjualan.index') }}">
                            <i class="ti-control-record"></i>Penjualan
                        </a>
                    </li>
                    @endif
                    @if(hasModuleAccess('fa-mutasi'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('fa-mutasi.index') }}">
                            <i class="ti-control-record"></i>Mutasi
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            <!-- BAST (hanya untuk admin & super admin, tidak untuk user) -->
            @if(hasModuleAccess('bast') && $role !== 'user')
            <li>
                <a href="{{ route('bast.index') }}">
                    <i data-feather="file-text" class="align-self-center menu-icon"></i><span>BAST</span>
                </a>
            </li>
            @endif

            <!-- General Affair -->
            @if(hasModuleAccess('ga-barang') || hasModuleAccess('ga-jasa-lembur') || hasModuleAccess('ga-ruang-meeting'))
            <li>
                <a href="javascript: void(0);">
                    <i data-feather="briefcase" class="align-self-center menu-icon"></i><span>General Affair</span>
                    <span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    @if(hasModuleAccess('ga-barang'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ga-barang.index') }}">
                            <i class="ti-control-record"></i>Permintaan Barang
                        </a>
                    </li>
                    @endif
                    @if(hasModuleAccess('ga-jasa-lembur'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ga-jasa-lembur.index') }}">
                            <i class="ti-control-record"></i>Jasa Lembur
                        </a>
                    </li>
                    @endif
                    @if(hasModuleAccess('ga-ruang-meeting'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ga-ruang-meeting.index') }}">
                            <i class="ti-control-record"></i>Ruang Meeting
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            <!-- Master Data (Super Admin only) -->
            @if($role === 'super_admin')
            <li>
                <a href="javascript: void(0);">
                    <i data-feather="settings" class="align-self-center menu-icon"></i><span>Master Data</span>
                    <span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('super-admin.module-access.index') }}">
                            <i class="ti-control-record"></i>Module Access
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('companies.index') }}">
                            <i class="ti-control-record"></i>Perusahaan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('departments.index') }}">
                            <i class="ti-control-record"></i>Departemen
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}">
                            <i class="ti-control-record"></i>User Management
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            <hr class="hr-dashed hr-menu">
            <li class="menu-label my-2">Settings</li>
            <li>
                <a href="{{ route('profile') }}">
                    <i data-feather="user" class="align-self-center menu-icon"></i><span>Profile</span>
                </a>
            </li>
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i data-feather="log-out" class="align-self-center menu-icon"></i><span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            </li>
        </ul>
    </div>
</div>
<!-- end left-sidenav-->
