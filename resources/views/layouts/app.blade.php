<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-dark.png') }}">
    <!-- Page Title  -->
    <title>GG Player Dashboard</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .nk-chat-panel {
            height: 361px !important;
        }

        .select2-selection__clear {
            display: none;
        }
    </style>
</head>

<body class="nk-body npc-invest bg-lighter ">
    <div class="nk-app-root">
        <!-- wrap @s -->
        <div class="nk-wrap ">
            <!-- main header @s -->
            <div class="nk-header nk-header-fluid is-theme">
                <div class="container-xl wide-xl">
                    <div class="nk-header-wrap">
                        <div class="nk-menu-trigger me-sm-2 d-lg-none">
                            <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="headerNav"><em
                                    class="icon ni ni-menu"></em></a>
                        </div>
                        <div class="nk-header-brand">
                            <a href="{{ auth()->user()->role === 'admin' ? '/admin/dashboard' : '/reseller/dashboard' }}"
                                class="logo-link">
                                <img class="logo-light logo-img" src="{{ asset('assets/images/logo.png') }}"
                                    srcset="{{ asset('assets/images/logo.png') }}" alt="logo">
                                <img class="logo-dark logo-img" src="{{ asset('assets/images/logo-dark.png') }}"
                                    srcset="{{ asset('assets/images/logo.png') }}" alt="logo-dark">
                            </a>
                        </div><!-- .nk-header-brand -->
                        <div class="nk-header-menu" data-content="headerNav">
                            <div class="nk-header-mobile">
                                <div class="nk-header-brand">
                                    <a href="{{ auth()->user()->role === 'admin' ? '/admin/dashboard' : '/reseller/dashboard' }}"
                                        class="logo-link">
                                        <img class="logo-light logo-img" src="{{ asset('assets/images/logo.png') }}"
                                            srcset="{{ asset('assets/images/logo2x.png') }} 2x" alt="logo">
                                        <img class="logo-dark logo-img"
                                            src="{{ asset('assets/images/logo-dark.png') }}"
                                            srcset="{{ asset('assets/images/logo-dark2x.png') }} 2x" alt="logo-dark">
                                    </a>
                                </div>
                                <div class="nk-menu-trigger me-n2">
                                    <a href="#" class="nk-nav-toggle nk-quick-nav-icon"
                                        data-target="headerNav"><em class="icon ni ni-arrow-left"></em></a>
                                </div>
                            </div>
                            <ul class="nk-menu nk-menu-main ui-s2">
                                <li class="nk-menu-item has-sub">
                                    <a href="{{ auth()->user()->role === 'admin' ? '/admin/dashboard' : '/reseller/dashboard' }}"
                                        class="nk-menu-link">
                                        <span class="nk-menu-text">Home</span>
                                    </a>
                                </li><!-- .nk-menu-item -->
                                @if (auth()->user()->role === 'admin')
                                    <li class="nk-menu-item">
                                        <a href="{{ route('resellers.index') }}" class="nk-menu-link">
                                            <span class="nk-menu-text">Resellers</span>
                                        </a>
                                    </li><!-- .nk-menu-item -->
                                @endif
                                <li class="nk-menu-item">
                                    <a href="{{ route('chat.index') }}" class="nk-menu-link">
                                        <span class="nk-menu-text">Chat</span>
                                    </a>
                                </li><!-- .nk-menu-item -->
                            </ul><!-- .nk-menu -->
                        </div><!-- .nk-header-menu -->
                        <div class="nk-header-tools">
                            <ul class="nk-quick-nav">
                                <li class="dropdown notification-dropdown"><a href="#"
                                        class="dropdown-toggle nk-quick-nav-icon" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <div class="icon-statusss" style="display: inline-flex">
                                            <em class="icon ni ni-bell"></em>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end dropdown-menu-s1"
                                        style="">
                                        <div class="dropdown-head"><span
                                                class="sub-title nk-dropdown-title">Notifications</span>
                                            {{-- <a
                                                href="#">Mark All as Read</a> --}}
                                        </div>
                                        <div class="dropdown-body">
                                            <div class="nk-notification">

                                            </div>
                                        </div>
                                        {{-- <div class="dropdown-foot center"><a href="#">View All</a></div> --}}
                                    </div>
                                </li>
                                <li class="dropdown user-dropdown order-sm-first">
                                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                                        <div class="user-toggle">
                                            <div class="user-avatar sm">
                                                <em class="icon ni ni-user-alt"></em>
                                            </div>
                                            <div class="user-info d-none d-xl-block">
                                                <div class="user-status">{{ ucfirst(auth()->user()->role) }}</div>
                                                <div class="user-name dropdown-indicator">{{ auth()->user()->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div
                                        class="dropdown-menu dropdown-menu-md dropdown-menu-end dropdown-menu-s1 is-light">
                                        <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                            <div class="user-card">
                                                <div class="user-avatar">
                                                    <span>{{ ucfirst(substr(auth()->user()->name, 0, 2)) }}</span>
                                                </div>
                                                <div class="user-info">
                                                    <span class="lead-text">{{ auth()->user()->name }}</span>
                                                    <span class="sub-text">{{ auth()->user()->email }}</span>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="dropdown-inner">
                                            <ul class="link-list">
                                                @if (auth()->user()->role === 'admin')
                                                    <li><a href="/admin/updatepassword"><em
                                                                class="icon ni ni-user-alt"></em><span>Update
                                                                Profile</span></a></li>

                                                    <li><a href="/admin/settings"><em
                                                                class="icon ni ni-setting-alt"></em><span>Setting</span></a>
                                                    </li>
                                                @else
                                                    <li><a href="{{ route('reseller.profile') }}"><em
                                                                class="icon ni ni-user-alt"></em><span>Update
                                                                Profile</span></a></li>
                                                @endif

                                                <li><a class="dark-mode-switch dark-switch" href="#"><em
                                                            class="icon ni ni-moon"></em><span>Dark Mode</span></a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="dropdown-inner">
                                            <ul class="link-list">
                                                <li><a
                                                        href="{{ auth()->user()->role === 'admin' ? url('/admin/logout') : route('reseller.logout') }}"><em
                                                            class="icon ni ni-signout"></em><span>Sign out</span></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li><!-- .dropdown -->

                            </ul><!-- .nk-quick-nav -->
                        </div><!-- .nk-header-tools -->
                    </div><!-- .nk-header-wrap -->
                </div><!-- .container-fliud -->
            </div>
            <!-- main header @e -->
            <!-- content @s -->
            <div class="nk-content nk-content-fluid">
                <div class="container-xl wide-xl">
                    @yield('content')
                </div>
            </div>
            <!-- content @e -->
            <!-- footer @s -->
            <div class="nk-footer nk-footer-fluid bg-lighter">
                <div class="container-xl">
                    <div class="nk-footer-wrap">
                        <div class="nk-footer-copyright"> &copy; {{ date('Y') }} GG Player.</a>
                        </div>

                    </div>
                </div>
            </div>
            <!-- footer @e -->
        </div>
        <!-- wrap @e -->
    </div>
    <!-- app-root @e -->

    <!-- JavaScript -->
    <script src="{{ asset('assets/js/bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/charts/gd-invest.js') }}"></script>
    <script src="{{ asset('js/chat.js') }}"></script>
    @yield('script_content')
</body>

</html>
