<x-app-layout :pageName="'Dashboard'">
    <style>
        .cardEmpty {
            display: flex !important;
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), url('img/no-invoice.png') center center no-repeat;
            justify-content: center !important;
            align-items: center !important;
            height: 50vh !important;


        }

        .card .empty {
            font-size: 2rem;

        }
        .program{
            display: flex;
            justify-content: space-between;
        }
    </style>
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title">
            <div class="row w-full w-100">
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
                {{-- @dd(session('user')); --}}
                Welcome <h1> {{ session('user')->Surname  }} {{ session('user')->Othernames }}
                </h1>
            </div>
        </div>
        <!-- Content End -->
        <div class="card mb-2 w-100">
            @if ($transDetails->isEmpty())
                <div class="card cardEmpty mb-2">
                    <p class="empty">No Record found</p>

                    <a class="btn btn-center btn-icon btn-icon-end btn-primary w-10"
                        href="{{ route('dashboard.create') }}">
                        <span>Apply Now!</span>
                        <i data-acorn-icon="chevron-right"></i>
                    </a>
                </div>
            @else
                <div class="card-body h-100 w-100">
                    <div class="program">
                        <h1 class="mb-0 pb-0 display-4" id="title">Programme Details</h1>
                        <a class="btn btn-center btn-icon btn-icon-end btn-primary w-20"
                            href="{{ route('dashboard.create') }}">
                            <span>Apply for Another Program</span>
                            <i data-acorn-icon="chevron-right"></i>
                        </a>

                    </div>
                    <br>
                    <nav class="breadcrumb-container w-100 d-inline-block" aria-label="breadcrumb">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Faculty</th>
                                        <th>Department</th>
                                        <th>Degree</th>
                                        <th>Specialization</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $index => $user)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $user->faculty }}</td>
                                            <td>{{ $user->department }}</td>
                                            <td>{{ $user->degree }}</td>
                                            <td>{{ $user->feildofinterest }}</td>
                                            <td>
                                                <a href="{{ route('dashboard.create', ['faculty' => $user->faculty, 'department' => $user->department, 'degree' => $user->degree, 'field' => $user->feildofinterest]) }}" class="btn btn-info">Apply</a>                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                    </nav>
                </div>

            @endif
            </table>
        </div>
    </div>

</x-app-layout>
