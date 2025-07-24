<x-admin-layout :pageName="'Dashboard'">
    <style>
        .cardEmpty {
            display: flex !important;
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), url('../img/no-invoice.png') center center no-repeat;
            justify-content: center !important;
            align-items: center !important;
            height: 50vh !important;


        }

        .card .empty {
            font-size: 2rem;

        }

        .program {
            display: flex;
            justify-content: space-between;
        }

        /* Table styling */
        #recordsTable {
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
        }

        #recordsTable thead {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center !important;
        }

        #recordsTable tbody tr {
            transition: background-color 0.3s ease;
        }

        #recordsTable tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Column Separator */
        #recordsTable th,
        #recordsTable td {
            border-right: 1px solid #ddd;
            /* Add border to separate columns */
        }

        #recordsTable th:last-child,
        #recordsTable td:last-child {
            border-right: none;
            /* Remove border for the last column */
        }


        /* Styling for buttons */
        .btn {
            border-radius: 5px;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .d-flex button {
            width: 100px;
        }

        /* Hover effect for buttons */
        .btn-primary:hover {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-warning:hover {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-success:hover {
            background-color: #28a745;
            border-color: #28a745;
        }

        /* Responsive table */
        @media (max-width: 768px) {

            #recordsTable td,
            #recordsTable th {
                font-size: 0.85rem;
            }

            .d-flex button {
                width: auto;
            }
        }

        .test {
            display: flex !important;
            justify-content: space-between !important;
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
                Welcome <h1> {{ session('admin_user')->fullname }}
                </h1>
            </div>
        </div>
        <!-- Content End -->
        <div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold text-center mb-6">Student Transcript</h2>

            <!-- Student Information -->
            <div class="bg-gray-100 p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-semibold mb-4">Student Information</h3>
                <div class="test">
                    <div>
                        <label class="font-semibold block">Surname:</label>
                        <input type="text" name="Surname" value="{{ $records->Surname }}"
                            class="border p-2 w-full rounded-md">
                    </div>

                    <div>
                        <label class="font-semibold block">Othernames:</label>
                        <input type="text" name="Othernames" value="{{ $records->Othernames }}"
                            class="border p-2 w-full rounded-md">
                    </div>

                    <div>
                        <label class="font-semibold block">Gender:</label>
                        <input type="text" name="sex" value="{{ $records->sex }}"
                            class="border p-2 w-full rounded-md">
                    </div>

                    <div>
                        <label class="font-semibold block">Faculty:</label>
                        <input type="text" name="faculty" value="{{ $records->faculty }}"
                            class="border p-2 w-full rounded-md">
                    </div>
                    <div>
                        <label class="font-semibold block">Department:</label>
                        <input type="text" name="department" value="{{ $records->department }}"
                            class="border p-2 w-full rounded-md">
                    </div>
                </div>

                <div class="test">
                    <div>
                        <label class="font-semibold block">Specialization:</label>
                        <input type="text" name="feildofinterest" value="{{ $records->feildofinterest }}"
                            class="border p-2 w-full rounded-md">
                    </div>
                    <div>
                        <label class="font-semibold block">Degree:</label>
                        <input type="text" name="degree" value="{{ $records->degree }}"
                            class="border p-2 w-full rounded-md">
                    </div>

                    <div>
                        <label class="font-semibold block">Degree:</label>
                        <input type="text" name="degree" value="{{ $records->degree }}" class="border p-2 w-full rounded-md">
                    </div>
                    <p><strong>Matric Number:</strong> {{ $records->matric }}</p>
                    <p><strong>Session Admitted:</strong> {{ $records->sessionadmin }}</p>
                </div>

            </div>

            <!-- Academic Results -->
            <div class="bg-gray-100 p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-semibold mb-4">Academic Results</h3>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border p-2">Course Code</th>
                                <th class="border p-2">Course Title</th>
                                <th class="border p-2">Units</th>
                                <th class="border p-2">Status</th>
                                <th class="border p-2">Score</th>
                                <th class="border p-2">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $result)
                                <tr class="border">
                                    <td class="border p-2">{{ $result->course->course_code ?? 'N/A' }}</td>
                                    <td class="border p-2">{{ $result->course->course_title ?? 'N/A' }}</td>
                                    <td class="border p-2">{{ $result->c_unit ?? 3 }}</td>
                                    <td class="border p-2">{{ $result->status }}</td>
                                    <td class="border p-2">{{ $result->score }}</td>
                                    <td class="border p-2">{{ $result->grade }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Academic Standing -->
            <div class="bg-gray-100 p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-semibold mb-4">Academic Standing</h3>
                <div class="flex justify-between">
                    <h5 class="text-lg">CGPA: <span
                            class="bg-blue-500 text-black bold px-3 py-1 rounded">{{ $cgpa ?? 'N/A' }}</span></h5>
                    <h5 class="text-lg">Class of Degree: <span
                            class="bg-green-500 text-white px-3 py-1 rounded">{{ $classOfDegree ?? 'N/A' }}</span></h5>
                </div>
            </div>

            <!-- Back to Dashboard Button -->
            <div class="text-center">
                <a href="{{ route('admin.dashboard') }}"
                    class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-900">Back to Dashboard</a>
            </div>
        </div>

    </div>


</x-admin-layout>
