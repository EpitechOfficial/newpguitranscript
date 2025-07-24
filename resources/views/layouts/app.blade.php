@props(['pageName'])
<!DOCTYPE html>
<html lang="en" data-footer="true" data-override='{"attributes": {"placement": "vertical" }}'>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Transcript Application | {{ $pageName }}</title>
    <meta name="description" content="Login Page" />
    <!-- Favicon Tags Start -->

    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-196x196.png') }}" sizes="196x196" />
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-32x32.png') }}" sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-16x16.png') }}" sizes="16x16" />
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-128.png') }}" sizes="128x128" />

    <!-- Favicon Tags End -->
    <!-- Font Tags Start -->
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

    <style>
        .no-arrow{
            display: flex;

        }
        .cartvalue{
            background: #0a2b4f;
            color: #fff;
            font-weight: bold;
            margin-left: 0.2rem;
            padding-left: 0.2rem;
            padding-right: 0.2rem;
            border-radius: 5px;
        }
        .white{
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
                        <div class="name">{{ session('user')->Surname }} {{ session('user')->Othernames }}
                            <div class="card-body h-100">
                                {{-- <h1>Welcome </h1> --}}
                                <p>{{ session('user')->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end user-menu wide">


                    </div>
                </div>
                <!-- User Menu End -->



                <!-- Menu Start -->
                <div class="menu-container flex-grow-1">

                    <ul id="menu" class="menu">
                        <li>
                            <a href="{{ route('dashboard') }}" data-href="#">
                                <i data-acorn-icon="home" class="icon" data-acorn-size="18"></i>
                                <span class="label">Dashboards</span>
                            </a>

                        </li>
                        <li>
                            <a href="{{ route('track') }}" data-href="#">
                                <i data-acorn-icon="destination" class="icon" data-acorn-size="18"></i>
                                <span class="label">Track Application</span>
                            </a>

                        </li>


                        <li>
                            <a href="{{ route('dashboard.create') }}" data-href="#">
                                <i data-acorn-icon="screen" class="icon" data-acorn-size="18"></i>
                                <span class="label">Apply New</span>
                            </a>
                        </li>


                        <li>
                            <a href="{{ URL('cart') }}">
                                <i data-acorn-icon="screen" class="icon" data-acorn-size="18"></i>
                                <span class="label">Cart</span>
                                <span class="cartvalue white">{{$cartItemCount}}</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ URL('transinvoice') }}" data-href="#">
                                <i data-acorn-icon="notebook-1" class="icon" data-acorn-size="18"></i>
                                <span class="label">Invoice</span>
                            </a>

                        </li>


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

                    <a href="{{ URL('cart') }}" class="cart">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                          </svg>
                          <span class="cartvalue">{{$cartItemCount}}</span>
                        </a>

                    <form method="post" action="{{ route('logout') }}" class="nav-link">
                        @csrf





                        <button class="trash logout" type="submit" name="logout">
                            <i class="fas fa-sign-out-alt"></i>Logout
                        </button>
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
