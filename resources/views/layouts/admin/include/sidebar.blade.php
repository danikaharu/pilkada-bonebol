<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <img src="{{ asset('template/img/illustrations/logo.png') }}" alt="" class="app-brand-logo"
                width="60">
            <span class="app-brand-text menu-text fw-bolder ms-2">PILKADA</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->is('admin/dashboard') ? ' active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>


        @can('view electoral district')
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Daerah Pemilihan</span>
            </li>
            <li
                class="menu-item {{ request()->is('admin/electoraldistrict', 'admin/electoraldistrict/*') ? ' active' : '' }}">
                <a href="{{ route('admin.electoraldistrict.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-building-house"></i>
                    <div data-i18n="Basic">Dapil</div>
                </a>
            </li>
        @endcan

        @can('view subdistrict')
            <li class="menu-item {{ request()->is('admin/subdistrict', 'admin/subdistrict/*') ? ' active' : '' }}">
                <a href="{{ route('admin.subdistrict.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-building-house"></i>
                    <div data-i18n="Basic">Kecamatan</div>
                </a>
            </li>
        @endcan

        @can('view village')
            <li class="menu-item {{ request()->is('admin/village', 'admin/village/*') ? ' active' : '' }}">
                <a href="{{ route('admin.village.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-building-house"></i>
                    <div data-i18n="Basic">Kelurahan/Desa</div>
                </a>
            </li>
        @endcan

        @can('view polling station')
            <li class="menu-item {{ request()->is('admin/pollingstation', 'admin/pollingstation/*') ? ' active' : '' }}">
                <a href="{{ route('admin.pollingstation.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-building-house"></i>
                    <div data-i18n="Basic">TPS</div>
                </a>
            </li>
        @endcan

        @can('view candidate')
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Pasangan Calon</span>
            </li>
            <li class="menu-item {{ request()->is('admin/candidate', 'admin/candidate/*') ? ' active' : '' }}">
                <a href="{{ route('admin.candidate.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-circle"></i>
                    <div data-i18n="Basic">Pasangan Calon</div>
                </a>
            </li>
        @endcan

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Perolehan Suara</span>
        </li>

        @can('view polling')
            <li class="menu-item {{ request()->is('admin/polling') ? ' active' : '' }}">
                <a href="{{ route('admin.polling.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-edit-alt"></i>
                    <div data-i18n="Basic">Perolehan Suara</div>
                </a>
            </li>
        @endcan

        @can('create polling')
            <li class="menu-item {{ request()->is('admin/polling/create') ? ' active' : '' }}">
                <a href="{{ route('admin.polling.create') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-edit-alt"></i>
                    <div data-i18n="Basic">Input Suara</div>
                </a>
            </li>
        @endcan

        <li class="menu-item {{ request()->is('admin/pollings/result/*') ? ' open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Basic">Hasil Perolehan Suara</div>
            </a>
            <ul class="menu-sub">
                @can('result polling station')
                    <li class="menu-item {{ request()->is('admin/pollings/result/pollingstation') ? ' active' : '' }}">
                        <a href="{{ route('admin.polling.result') }}" class="menu-link">
                            <div>Per Tps</div>
                        </a>
                    </li>
                @endcan

                @can('result village')
                    <li class="menu-item {{ request()->is('admin/pollings/result/village') ? ' active' : '' }}">
                        <a href="{{ route('admin.polling.resultVillage') }}" class="menu-link">
                            <div>Per Desa</div>
                        </a>
                    </li>
                @endcan

                @can('result subdistrict')
                    <li class="menu-item {{ request()->is('admin/pollings/result/subdistrict') ? ' active' : '' }}">
                        <a href="{{ route('admin.polling.resultSubdistrict') }}" class="menu-link">
                            <div>Per Kecamatan</div>
                        </a>
                    </li>
                @endcan

                @can('result electoral district')
                    <li class="menu-item {{ request()->is('admin/pollings/result/electoraldistrict') ? ' active' : '' }}">
                        <a href="{{ route('admin.polling.resultElectoraldistrict') }}" class="menu-link">
                            <div>Per Dapil</div>
                        </a>
                    </li>
                @endcan

                @can('result all')
                    <li class="menu-item {{ request()->is('admin/pollings/result/all') ? ' active' : '' }}">
                        <a href="{{ route('admin.polling.resultAll') }}" class="menu-link">
                            <div>Keseluruhan</div>
                        </a>
                    </li>
                @endcan
            </ul>
        </li>

        <li class="menu-item {{ request()->is('admin/pollings/graphic/*') ? ' open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-doughnut-chart"></i>
                <div data-i18n="Basic">Grafik Perolehan Suara</div>
            </a>
            <ul class="menu-sub">
                @can('graphic polling station')
                    <li class="menu-item {{ request()->is('admin/pollings/graphic/pollingstation') ? ' active' : '' }}">
                        <a href="{{ route('admin.polling.graphic') }}" class="menu-link">
                            <div>Per Tps</div>
                        </a>
                    </li>
                @endcan

                @can('graphic village')
                    <li class="menu-item {{ request()->is('admin/pollings/graphic/village') ? ' active' : '' }}">
                        <a href="{{ route('admin.polling.graphicVillage') }}" class="menu-link">
                            <div>Per Desa</div>
                        </a>
                    </li>
                @endcan

                @can('graphic subdistrict')
                    <li class="menu-item {{ request()->is('admin/pollings/graphic/subdistrict') ? ' active' : '' }}">
                        <a href="{{ route('admin.polling.graphicSubdistrict') }}" class="menu-link">
                            <div>Per Kecamatan</div>
                        </a>
                    </li>
                @endcan

                @can('graphic electoral district')
                    <li class="menu-item {{ request()->is('admin/pollings/graphic/electoraldistrict') ? ' active' : '' }}">
                        <a href="{{ route('admin.polling.graphicElectoralDistrict') }}" class="menu-link">
                            <div>Per Dapil</div>
                        </a>
                    </li>
                @endcan

                @can('graphic all')
                    <li class="menu-item {{ request()->is('admin/pollings/graphic/all') ? ' active' : '' }}">
                        <a href="{{ route('admin.polling.graphicAll') }}" class="menu-link">
                            <div>Keseluruhan</div>
                        </a>
                    </li>
                @endcan
            </ul>
        </li>

        @can('view user')
            <!-- Pengguna -->
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Pengguna</span>
            </li>
            <li class="menu-item {{ request()->is('admin/user', 'admin/user/*') ? ' active' : '' }}">
                <a href="{{ route('admin.user.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="Basic">Pengguna</div>
                </a>
            </li>
        @endcan

        @can('view role')
            <li class="menu-item {{ request()->is('admin/role', 'admin/role/*') ? ' active' : '' }}">
                <a href="{{ route('admin.role.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-user-account"></i>
                    <div data-i18n="Basic">Role</div>
                </a>
            </li>
        @endcan

    </ul>
</aside>
