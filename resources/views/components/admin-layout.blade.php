@props(['pageName'])
<!DOCTYPE html>
<html lang="en" data-footer="true" data-override='{"attributes": {"placement": "vertical" }}'>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Transcript Application | {{ $pageName }}</title>
    <meta name="description" content="Login Page" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon Tags Start -->

    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-196x196.png') }}" sizes="196x196" />
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-32x32.png') }}" sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-16x16.png') }}" sizes="16x16" />
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-128.png') }}" sizes="128x128" />

    <!-- Favicon Tags End -->
    <!-- Font Tags Start -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS (for dismissible alerts) -->

    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="font/CS-Interface/style.css" />
    <!-- Font Tags End -->
    <!-- Vendor Styles Start -->
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/OverlayScrollbars.min.css') }}" />
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Vendor Styles End -->
    <!-- Template Base Styles Start -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
    <!-- Template Base Styles End -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.32/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.32/sweetalert2.min.css">

    <style>
        .no-arrow {
            display: flex;

        }

        .cartvalue {
            background: #0a2b4f;
            color: #fff;
            font-weight: bold;
            margin-left: 0.2rem;
            padding-left: 0.2rem;
            padding-right: 0.2rem;
            border-radius: 5px;
        }

        .white {
            background: #fff !important;
            color: #0a2b4f !important;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
    <script src="{{ asset('js/base/loader.js') }}"></script>
</head>

<body>
    <div id="root">


        <div id="nav" class="nav-container d-flex">
            <div class="nav-content d-flex">
                <!-- Logo Start -->
                <div class="logo position-relative">
                    <a href="Dashboards.Default.html">
                        <!-- Logo can be added directly -->
                        <!-- <img src="img/logo/logo-white.svg" alt="logo" /> -->

                        <!-- Or added via css to provide different ones for different color themes -->
                        <div class="img"></div>
                    </a>
                </div>
                <!-- Logo End -->



                <!-- User Menu Start -->
                <div class="user-container d-flex">
                    <div class="d-flex user position-relative" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <img class="profile" alt="profile" src="{{ asset('img/profile-11.webp') }}" />
                        <div class="name"> {{ session('admin_user')->username }}
                            <div class="card-body h-100">
                                {{-- <h1>Welcome </h1> --}}
                                <p> {{ session('admin_user')->fullname }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end user-menu wide">


                    </div>
                </div>
                <!-- User Menu End -->



                <!-- Menu Start -->
                <div class="menu-container flex-grow-1">
                    @php
                        $role = (session('admin_user')->role) ?? null;
                        $mass = true; // Assuming mass transcript is available for all roles
                    @endphp

                    <ul id="menu" class="menu">
                        <!-- Dashboard - Available to all roles -->
                        {{-- <li>
                            <a href="{{ route('admin.dashboard') }}" data-href="#">
                                <i data-acorn-icon="home" class="icon" data-acorn-size="18"></i>
                                <span class="label">Dashboard</span>
                            </a>
                        </li> --}}

                        @if($role == 2) {{-- TO Role --}}
                        <li>
                            <a href="{{ route('admin.dashboard.to') }}" data-href="#">
                                <i data-acorn-icon="file-text" class="icon" data-acorn-size="18"></i>
                                <span class="label">Transcript Officer</span>
                            </a>
                        </li>
                        @endif

                        @if($role == 3) {{-- KI Role --}}
                        <li>
                            <a href="{{ route('admin.dashboard.ki') }}" data-href="#">
                                <i data-acorn-icon="inbox" class="icon" data-acorn-size="18"></i>
                                <span class="label">Key-In Request</span>
                            </a>
                        </li>
                        @endif

                        @if($role == 4) {{-- PO Role --}}
                        <li>
                            <a href="{{ route('admin.dashboard_po') }}" data-href="#">
                                <i data-acorn-icon="settings" class="icon" data-acorn-size="18"></i>
                                <span class="label">Processing Officer</span>
                            </a>
                        </li>
                        @endif

                        @if($role == 5) {{-- FO Role --}}
                        <li>
                            <a href="{{ route('admin.dashboard.fo') }}" data-href="#">
                                <i data-acorn-icon="check-circle" class="icon" data-acorn-size="18"></i>
                                <span class="label">Filing Officer</span>
                            </a>
                        </li>
                        @endif

                        @if($role == 6) {{-- Transreceive Role --}}
                        <li>
                            <a href="{{ route('admin.transrecevedashboard') }}" data-href="#">
                                <i data-acorn-icon="inbox" class="icon" data-acorn-size="18"></i>
                                <span class="label">Transcript Request</span>
                            </a>
                        </li>
                        @endif

                        @if($role == 7) {{-- Record Processed Role --}}
                        <li>
                            <a href="{{ route('admin.recordProcesseds') }}" data-href="#">
                                <i data-acorn-icon="home" class="icon" data-acorn-size="18"></i>
                                <span class="label">Record Processed</span>
                            </a>
                        </li>
                        @endif

                        <!-- Common menu items for all roles -->
                        @if($role == 7 || $role == 2) {{-- Record Processed and Approved roles --}}
                        <li>
                            <a href="{{ route('admin.recordApproved') }}" data-href="#">
                                <i data-acorn-icon="check" class="icon" data-acorn-size="18"></i>
                                <span class="label">Approved Record</span>
                            </a>
                        </li>
                        @endif

                        @if ($role == 3)
                        
                        <!-- Upload Result - KI -->
                        <li>
                            <a href="{{ route('result_old.upload_form') }}" data-href="#">
                                <i data-acorn-icon="keyboard" class="icon" data-acorn-size="18"></i>
                                <span class="label">Upload Result</span>
                            </a>
                        </li>
                        @endif

                        <!-- Edit Result - Available to specific roles -->
                        @if(in_array($role, [3, 2, 6, 7])) {{-- KI, PO, Help, FO roles --}}
                        <li>
                            <a href="{{ route('admin.edit_transcript_realtime') }}" data-href="#">
                                <i data-acorn-icon="edit" class="icon" data-acorn-size="18"></i>
                                <span class="label">Edit/View Result</span>
                            </a>
                        </li>
                        @endif

                        @if ($mass && ($role == 7 || $role == 2))
                        
                        <!-- Students by Department - Available to all roles -->
                        <li>
                            <a href="{{ route('admin.students_by_department') }}" data-href="#">
                                <i data-acorn-icon="print" class="icon" data-acorn-size="18"></i>
                                <span class="label">2025 Convocation</span>
                            </a>
                        </li>
                        @endif

                     

                    </ul>
                </div>
                <!-- Menu End -->

                <!-- Mobile Buttons Start -->
                <div class="mobile-buttons-container">
                    <!-- Scrollspy Mobile Button Start -->
                    <a href="#" id="scrollSpyButton" class="spy-button" data-bs-toggle="dropdown">
                        <i data-acorn-icon="menu-dropdown"></i>
                    </a>
                    <!-- Scrollspy Mobile Button End -->

                    <!-- Scrollspy Mobile Dropdown Start -->
                    <div class="dropdown-menu dropdown-menu-end" id="scrollSpyDropdown"></div>
                    <!-- Scrollspy Mobile Dropdown End -->

                    <!-- Menu Button Start -->
                    <a href="#" id="mobileMenuButton" class="menu-button">
                        <i data-acorn-icon="menu"></i>
                    </a>
                    <!-- Menu Button End -->
                </div>
                <!-- Mobile Buttons End -->
            </div>
            <div class="nav-shadow"></div>
        </div>
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Search -->
            <div id="nav1" onmouseover="closeCity(event, 'Paris')">

                <!--
    <div class="top-logo">

      <img style="width: 30px;" src="img/ui-logo.png" class="logo">

  </div>
-->
                <img style="width: 30px;" src="{{ asset('img/ui-logo.png') }}" class="logo">
                <div class="topbar-divider d-none d-sm-block"></div>
                <img style="width: 40px; margin-right:1rem;margin-bottom:0.3rem; " src="{{ asset('img/logo.png') }}"
                    class="logo">
                <h1 class="h3 m-0 font-weight-100 text-primary" style="">Transcript Processing Application</h1>
                <!--
  <div class="top-logo">

      <img style="width: 50px;" src="img/logo.png" class="logo">

  </div>
-->
            </div>
            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">


                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">


                    <form method="post" action="{{ route('admin.logout') }}" class="nav-link">
                        @csrf
                        <button class="trash logout" type="submit" name="logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>

                    {{-- <button class="trash logout" type="submit" name="logout">
                            <i class="fas fa-sign-out-alt"></i>Logout
                        </button> --}}
                    </form>



                </li>

            </ul>

        </nav>


        <main>
            {{ $slot }}
        </main>

        <!-- Footer Start -->
        {{-- <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <p>Transcript Processing Application</p>
                    </div>
                </div>
            </div>
        </footer> --}}
        <!-- Footer End -->

    </div>



    <!-- Vendor Scripts Start -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/vendor/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/vendor/OverlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('js/vendor/autoComplete.min.js') }}"></script>
    <script src="{{ asset('js/vendor/clamp.min.js') }}"></script>

    <script src="{{ asset('icon/acorn-icons-commerce.js') }}"></script>
    <script src="{{ asset('icon/acorn-icons.js') }}"></script>
    <script src="{{ asset('icon/acorn-icons-interface.js') }}"></script>

    <!-- Vendor Scripts End -->

    <!-- Template Base Scripts Start -->
    <script src="{{ asset('js/base/helpers.js') }}"></script>
    <script src="{{ asset('js/base/globals.js') }}"></script>
    <script src="{{ asset('js/base/nav.js') }}"></script>
    <script src="{{ asset('js/base/search.js') }}"></script>
    <script src="{{ asset('js/base/settings.js') }}"></script>
    <!-- Template Base Scripts End -->
    <!-- Page Specific Scripts Start -->

    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <!-- Page Specific Scripts End -->
</body>

</html>
