<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Panel admin {{ config('app.name', 'DPRD Kepri') }}">
    <title>@yield('title', 'Dashboard') | {{ config('app.name', 'DPRD Kepri') }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}">
    <style>
        .nxl-link.active,
        .nxl-submenu .nxl-link.active {
            color: var(--bs-primary);
            font-weight: 700;
        }

        .permission-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        html.minimenu .nxl-navigation .nxl-navbar .nxl-item .nxl-link {
            justify-content: center !important;
            align-items: center !important;
            padding-left: 5px !important;
            padding-right: 5px !important;
            flex-direction: column !important;
        }

        html.minimenu .nxl-navigation .nxl-navbar .nxl-item .nxl-link .nxl-micon {
            margin-right: 0 !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
        }

        html.minimenu .nxl-navigation .nxl-navbar .nxl-item .nxl-link .nxl-mtext {
            text-align: center !important;
            margin-top: 4px !important;
            white-space: normal !important;
            line-height: 1.2 !important;
            margin-left: 0 !important;
        }

        html.minimenu .nxl-navigation .nxl-navbar .nxl-item .nxl-link .nxl-arrow {
            display: none !important; /* Hilangkan panah drop down saat layout sempit */
        }
    </style>
    @yield('styles')
</head>

<body>
    <nav class="nxl-navigation">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="{{ route('admin.dashboard') }}" class="b-brand">
                    <img src="{{ asset('assets/images/logo-full.png') }}" alt="Logo" class="logo logo-lg">
                    <img src="{{ asset('assets/images/logo-abbr.png') }}" alt="Logo" class="logo logo-sm">
                </a>
            </div>
            <div class="navbar-content">
                @include('components.admin.sidebar', ['menus' => $sidebarMenus ?? collect()])
            </div>
        </div>
    </nav>

    <header class="nxl-header">
        <div class="header-wrapper">
            <div class="header-left d-flex align-items-center gap-4">
                <div class="nxl-head-mobile-toggler" id="mobile-collapse">
                    <div class="hamburger hamburger--arrowturn">
                        <div class="hamburger-box">
                            <div class="hamburger-inner"></div>
                        </div>
                    </div>
                </div>
                <div class="nxl-navigation-toggle">
                    <a href="javascript:void(0);" id="menu-mini-button">
                        <i class="feather-align-left"></i>
                    </a>
                </div>
            </div>
            <div class="header-right ms-auto">
                <div class="dropdown">
                    <a href="javascript:void(0);" class="d-flex align-items-center gap-3" data-bs-toggle="dropdown">
                        <div class="avatar-text avatar-md bg-primary text-white">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="text-start d-none d-md-block">
                            <div class="fw-semibold">{{ auth()->user()?->name }}</div>
                            <div class="fs-12 text-muted">{{ auth()->user()?->getRoleNames()->join(', ') ?: 'Tanpa role' }}</div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-item-text">
                            <div class="fw-semibold">{{ auth()->user()?->email }}</div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST" class="px-3">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm w-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="nxl-container">
        <div class="nxl-content">
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">@yield('page_title', 'Dashboard')</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item">@yield('page_title', 'Dashboard')</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    @yield('page_actions')
                </div>
            </div>

            <div class="main-content">
                @include('components.admin.flash')
                @yield('content')
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/js/common-init.min.js') }}"></script>

    {{-- Accessibility Toolbar --}}
    <x-accessibility-toolbar />

    @yield('scripts')
</body>

</html>
