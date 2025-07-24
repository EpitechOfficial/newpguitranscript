<!DOCTYPE html>
<html lang="en" data-footer="true" data-override='{"attributes": {"placement": "vertical" }}'>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Transcript Application | Dashboard</title>
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
    <link rel="stylesheet" href="{{ asset('font/CS-Interface/style.css') }}" />
    <!-- Font Tags End -->
    <!-- Vendor Styles Start -->
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/OverlayScrollbars.min.css') }}" />
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <!-- Vendor Styles End -->
    <!-- Template Base Styles Start -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
    <!-- Template Base Styles End -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
    <script src="{{ asset('js/base/loader.js') }}"></script>

    <style>
        .form-control {
            margin-bottom: 20px;
            }
            label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            }
            input[type="file"] {
            border: 1px solid #ccc;
            padding: 8px;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            }
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .container-full-height {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
        }

        .checker {
            color: #0a2b4f;
            margin-top: 2rem;
            font-size: 1.5rem;
            text-align: center;
            font-weight: bold;
        }

        #feechecker {
            background: #0a2b4f;
            border-radius: 50px;
            padding: 3rem;
            display: flex;
            flex-direction: column;
        }

        #feechecker label {
            color: #fff;
        }

        #feechecker form .btn {
            border: 2px solid #FFCA42 !important;
        }

        #myTable th {
            text-align: left !important;
            background: #0a2b4f;
            color: #fff;
        }

        #myTable td {
            text-align: left !important;
        }

        .table th:first-child {
            border-top-left-radius: 10px;
        }

        .table th:last-child {
            border-top-right-radius: 10px;
        }

        .table tr:hover {
            background-color: #01314852;
            border: 2px solid #f1f1f1 !important;
            color: #000;
        }

        .table a {
            color: #000;
        }

        #navchecker {
            background: #fff;
            display: flex;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .left {
            background: #0a2b4f;
        }

        .lr {
            display: flex;
        }

        #form {
            background: #fff;
            padding: 3rem;
            display: flex;
            flex-direction: column;
        }

        #form label {
            color: #0a2b4f;
            display: block;
            font-weight: bold;
        }

        .bold {
            margin-bottom: 1rem;
            font-weight: bold;
        }

        #form form .btn {
            background: #03182e !important;
            border: 1px solid #03182e !important;
            transition: 0.3s ease-in-out !important;
            width: 50% !important;
            text-align: center !important;
            padding-left: 0 !important;
        }

        #form form .btn:hover {
            background: #0a2b4f;
            border: 1px solid #0a2b4f !important;
            color: #fff;
        }

        .left {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #000;
            background: #e4f1ff;
            padding: 2rem;
        }

        .left .head {
            font-family: "Nunito Sans", sans-serif;
            font-size: 2rem;
            padding: 1rem;
            text-align: center;
            font-weight: 700;
            color: #000;
        }

        .right #form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .right #form i {
            background: #03182e !important;
            color: #fff;
            font-size: 2rem;
            padding: 1rem;
            border-radius: 5rem;
            margin-bottom: 1rem;
        }

        .right #form .btn {
            width: 100% !important;
        }

        .box {
            margin: auto;
            max-width: 1050px;
            padding: 1rem;
            padding-bottom: 0 !important;
            overflow: auto;
        }

        .box input {
            padding-left: 2rem !important;
            margin-bottom: 0.5rem;
            border-radius: 1rem !important;
            outline: none;
            border: 1px solid #0a2b4f;
        }

        .mr {
            margin-right: 1rem;
        }

        .ml {
            margin-left: 1rem;
        }

        li {
            list-style: square !important;
            margin-bottom: 1rem;
        }

        .reg {
            color: #03182e;
            text-decoration: underline;
        }

        .noacc {
            font-size: 0.8rem;
        }

        @media (max-width: 950px) {
            .lr {
                display: block;
            }
        }
    </style>
</head>

<body>
    <div id="root" class="container-full-height">
        <div id="nav" class="nav-container d-flex">
            <div class="nav-content d-flex">
                <!-- Logo Start -->
                <div class="logo position-relative">
                    <a href="{{ route('dashboard') }}">
                        <!-- Logo can be added directly -->
                        <!-- <img src="img/logo/logo-white.svg" alt="logo" /> -->

                        <!-- Or added via css to provide different ones for different color themes -->
                        <div class="img"></div>
                    </a>
                </div>
                <!-- Logo End -->

                <!-- User Menu Start -->
                <div class="user-container d-flex">
                    <div class="d-flex user position-relative" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="profile" alt="profile" src="{{ asset('img/profile-11.webp') }}" />
                        <div class="name">{{ session('user')->Surname }} {{ session('user')->Other_names }}
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
                            <ul id="dashboards">
                                <li>
                                    <a href="#">
                                        <span class="label">Test</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="label">Test2</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="label">Test3</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.create') }}" data-href="#">
                                <i data-acorn-icon="screen" class="icon" data-acorn-size="18"></i>
                                <span class="label">Apply</span>
                            </a>
                        </li>
                        <li>
                            <a href="#pages" data-href="#">
                                <i data-acorn-icon="notebook-1" class="icon" data-acorn-size="18"></i>
                                <span class="label">Pages</span>
                            </a>
                        </li>
                        <li>
                            <a href="#blocks" data-href="#">
                                <i data-acorn-icon="grid-5" class="icon" data-acorn-size="18"></i>
                                <span class="label">Blocks</span>
                            </a>
                        </li>
                        <li class="mega">
                            <a href="#interface" data-href="#">
                                <i data-acorn-icon="pocket-knife" class="icon" data-acorn-size="18"></i>
                                <span class="label">Interface</span>
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
                <img style="width: 30px;" src="{{ asset('img/ui-logo.png') }}" class="logo">
                <div class="topbar-divider d-none d-sm-block"></div>
                <img style="width: 40px; margin-right:1rem;margin-bottom:0.3rem; " src="{{ asset('img/logo.png') }}" class="logo">
                <h1 class="h3 m-0 font-weight-100 text-primary" style="">Transcript Processing Application</h1>
            </div>
            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
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
            <div class="container">
                <div class="">
                    <!-- Title and Top Buttons Start -->
                    <div class="page-title-">
                        <div class="row">
                            <!-- Title Start -->
                            <div class="">
                                <h1 class="mb-0 pb-0 display-4" id="title">Dashboard</h1>
                                <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                                </nav>
                            </div>
                            <!-- Title End -->
                        </div>
                    </div>
                    <!-- Title and Top Buttons End -->
                    <!-- Content Start -->
                    <div class="card mb-2">
                        <div class="card-body h-100">
                            Welcome <h1>{{ session('user')->Surname }} {{ session('user')->Other_names }}</h1>
                        </div>
                    </div>
                    <!-- Content End -->
                    <div class="card mb-2">
                        <div class="card-body h-100">
                            <h1 class="mb-0 pb-0 display-4" id="title">Transcript Request</h1>
                            <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                                <form action="{{ route('dashboard.create') }}" method="POST">
                                    @csrf
                                    <h2 class="mt-4">Please provide details of your Transcript request and add to cart</h2>
                                    <div class="mt-10">
                                        <div class="row">
                                            <div class="col">
                                                <label for="surname">Surname </label>
                                                <input type="text" id="surname" name="surname" class="form-control" required placeholder="Please enter your Surname">
                                            </div>
                                            <div class="col">
                                                <label for="othernames">Other Names</label>
                                                <input type="text" id="othernames" name="othernames" class="form-control" required placeholder="Provide your other names">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-10">
                                        <div class="row">
                                            <div class="col">
                                                <label for="faculty">Faculty </label>
                                                <input type="text" id="faculty" name="faaculty" class="form-control" required placeholder="Provide your Faculty">
                                            </div>
                                            <div class="col">
                                                <label for="department">Department</label>
                                                <input type="text" id="department" name="department" class="form-control" required placeholder="Provide your Department">
                                            </div>
                                            <div class="col">
                                                <label for="degree">Degree</label>
                                                <input type="text" id="degree" name="degree" class="form-control" required placeholder="Provide your Degree">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-10">
                                        <div class="row">
                                            <div class="col">
                                                <label for="specialization">Specializtion </label>
                                                <input type="text" id="specialization" name="specialization" class="form-control" required placeholder="Provide your Faculty">
                                            </div>
                                            <div class="col">
                                                <label for="session_of_entry">Session of Entry</label>
                                                <input type="text" id="session_of_entry" name="session_of_entry" class="form-control" required placeholder="Provide your Department">
                                            </div>
                                            <div class="col">
                                                <label for="degree">Degree</label>
                                                <input type="text" id="degree" name="degree" class="form-control" required placeholder="Provide your Degree">
                                            </div>
                                        </div>
                                    </div>
                                    
                                            <div class="col">
                                                <label for="specialization">Specialization</label>
                                                <input type="text" id="specialization" name="specialization" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-10">
                                        <div class="row">
                                            <div class="col">
                                                <label for="specialization">Specialization</label>
                                                <input type="text" id="specialization" name="specialization" required>
                                            </div>
                                            <div class="col">
                                                <label for="session_of_entry">Session of Entry</label>
                                                <input type="text" id="session_of_entry" name="session_of_entry" required>
                                            </div>
                                            <div class="col">
                                                <label for="session_of_graduation">Session of Graduation</label>
                                                <input type="text" id="session_of_graduation" name="session_of_graduation" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label for="transcript_type">Transcript Type</label>
                                        <select id="transcript_type" name="transcript_type">
                                            @foreach ($requestTypes as $requestTypes)
                                                <option value="{{ $requestTypes->id }}">{{ $requestTypes->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mt-4">
                                        <label for="number_of_copies">Number of Copies</label>
                                        <select id="number_of_copies" name="number_of_copies">
                                            <option value="1">One (1)</option>
                                            <option value="2">Two (2)</option>
                                            <option value="3">Three (3)</option>
                                            <option value="4">Four (4)</option>
                                            <option value="5">Five (5)</option>
                                        </select>
                                    </div>
                                    <div class="mt-4">
                                        <label for="mode_of_delivery">Mode of Delivery</label>
                                        <select id="mode_of_delivery" name="mode_of_delivery">
                                            <option value="Physical">Physical</option>
                                            <option value="E-copy">E-copy</option>
                                        </select>
                                    </div>
                                    <div class="mt-4">
                                        <button class="btn btn-primary" type="submit">Add to Cart</button>
                                    </div>
                                </form>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        
    </div>

    <script src="{{ asset('js/vendor/pace.min.js') }}"></script>
    <script src="{{ asset('js/vendor/feather.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.min.js') }}"></script>
    <script src="{{ asset('js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/vendor/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.hotkeys.js') }}"></script>
    <script src="{{ asset('js/vendor/nouislider.min.js') }}"></script>
    <script src="{{ asset('js/vendor/OverlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('js/vendor/notify.min.js') }}"></script>
    <script src="{{ asset('js/vendor/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('js/vendor/scrollspy.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.acorn.tip.js') }}"></script>
    <script src="{{ asset('js/vendor/popper.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('js/acornui.config.js') }}"></script>
    <script src="{{ asset('js/acornui.core.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>
