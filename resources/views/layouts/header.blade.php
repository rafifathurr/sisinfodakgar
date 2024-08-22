<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between mx-lg-5">

        <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto me-xl-0">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <img src="{{ asset('img/korlantas-mabes.png') }}" alt="">
            <h1 class="sitename">Sisinfodakgar</h1><span></span>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li>
                    <a href="{{ url('/') }}" class="@if (Route::currentRouteName() == 'home') active @endif">
                        Beranda
                    </a>
                </li>
                @if (Auth::check())
                    <li class="dropdown">
                        <a href="#" class="@if (Route::currentRouteName() == 'archieve.mail.incoming-mail.index' ||
                                Route::currentRouteName() == 'archieve.mail.outgoing-mail.index') active @endif"><span>Arsip</span>
                            <i class="bi bi-chevron-down toggle-dropdown"></i>
                        </a>
                        <ul>
                            <li><a href="{{ route('archieve.album.index') }}">Album Dokumentasi</a>
                            </li>
                            <li class="dropdown">
                                <a href="#"><span>Persuratan</span>
                                    <i class="bi bi-chevron-down toggle-dropdown"></i>
                                </a>
                                <ul>
                                    <li><a href="{{ route('archieve.mail.incoming-mail.index') }}">Surat Masuk</a>
                                    </li>
                                    <li><a href="{{ route('archieve.mail.outgoing-mail.index') }}">Surat Keluar</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    @if (Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                        <li class="dropdown">
                            <a href="#"
                                class="@if (Route::currentRouteName() == 'master.classification.index' ||
                                        Route::currentRouteName() == 'master.type-mail-content.index' ||
                                        Route::currentRouteName() == 'master.institution.index') active @endif"><span>Master</span>
                                <i class="bi bi-chevron-down toggle-dropdown"></i>
                            </a>
                            <ul>
                                <li class="dropdown">
                                    <a href="#"><span>Master Persuratan</span>
                                        <i class="bi bi-chevron-down toggle-dropdown"></i>
                                    </a>
                                    <ul>
                                        <li><a href="{{ route('master.classification.index') }}">Klasifikasi</a></li>
                                        <li><a href="{{ route('master.type-mail-content.index') }}">Tipe Surat</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('master.institution.index') }}">Instansi Polri</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('user-management.index') }}"
                                class="@if (Route::currentRouteName() == 'user-management.index') active @endif">Manajemen User</a></li>
                    @endif
                    <li class="dropdown">
                        <a href="#"><span>{{ Auth::user()->name }}</span>
                            <i class="bi bi-chevron-down toggle-dropdown"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user animated fadeIn p-2 rounded-2">
                            <div class="dropdown-user-scroll scrollbar-outer">
                                <li>
                                    <div class="user-box text-center">
                                        <div class="avatar-lg">
                                            <img src="{{ asset('img/presisisi.png') }}" alt="image profile"
                                                class="avatar-img p-1 bg-grey rounded-circle my-2" width="100"
                                                height="80" />
                                        </div>
                                        <div class="u-text my-auto">
                                            <p>{{ Auth::user()->name }}</p>
                                            <p class="text-muted">{{ Auth::user()->employee_number }}</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="text-center">
                                    <a class="text-center" href="{{ route('logout') }}">Logout</a>
                                </li>
                            </div>
                        </ul>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Log In</a></li>
                @endif
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
    </div>
</header>
