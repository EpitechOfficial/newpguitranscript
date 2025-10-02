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

        table {
            width: 100% !important;
        }

        .btn-approve {
            display: flex !important;
            justify-content: center !important;
        }

        .address-section {
            margin-top: 1.5rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
        }

        .address-field {
            margin-bottom: 1rem;
        }

        .address-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .address-field textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            min-height: 80px;
            resize: vertical;
        }

        .courier-item {
            background-color: #fff;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            margin-bottom: 0.75rem;
        }

        .courier-item label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .courier-item textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            min-height: 70px;
            resize: vertical;
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

        <div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold text-center mb-6">Student Transcript Record</h2>

            <!-- Student Information -->
            <div class="p-6 shadow-md mb-6">
                <div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- <p><strong>Name:</strong>
                            {{ $biodata->othername && $biodata->surname ? $biodata->othername . ' ' . $biodata->surname : $biodata->name }}
                        </p>
                        <p><strong>Gender:</strong> {{ $gender ??$biodata->sex}}</p>
                        <p><strong>Matric Number:</strong> {{ $biodata->matric }}</p>
                        <p><strong>Session Admitted:</strong>
                            {{ $biodata->yr_of_entry ?? $results->first()->yr_of_entry }}</p>
                        <p><strong>Faculty:</strong>
                            {{ $biodata->faculty ?? ($results->first()->faculty->faculty ?? 'N/A') }}</p>
                        <p><strong>Department:</strong>
                            {{ $biodata->department ?? ($results->first()->department->department ?? 'N/A') }}</p> --}}

                        @if ($biodata)
                            <p><strong>Name:</strong>
                                {{ $biodata->Othernames && $biodata->Surname ? $biodata->Othernames . ' ' . $biodata->Surname : $biodata->name ?? 'N/A' }}
                            </p>
                            <p><strong>Gender:</strong> {{ $gender ?? ($biodata->sex ?? 'N/A') }}</p>
                            <p><strong>Matric Number:</strong> {{ $biodata->matric ?? 'N/A' }}</p>
                            <p><strong>Session Admitted:</strong>
                                {{ $biodata->sessionadmin ?? ($results->first()->sec ?? $results->first()->yr_of_entry) }}
                            </p>
                            <p><strong>Faculty:</strong>
                                {{ $biodata->faculty ?? ($results->first()->faculty->faculty ?? 'N/A') }}
                            </p>
                            <p><strong>Department:</strong>
                                {{ $biodata->department ?? ($results->first()->department->department ?? 'N/A') }}
                            </p>
                        @else
                            <p><strong>Name:</strong> N/A</p>
                            <p><strong>Gender:</strong> {{ $gender ?? 'N/A' }}</p>
                            <p><strong>Matric Number:</strong> N/A</p>
                            <p><strong>Session Admitted:</strong> {{ $results->first()->yr_of_entry ?? 'N/A' }}</p>
                            <p><strong>Faculty:</strong> {{ $results->first()->faculty->faculty ?? 'N/A' }}</p>
                            <p><strong>Department:</strong> {{ $results->first()->department->department ?? 'N/A' }}</p>
                        @endif


                    </div>

                </div>

                <hr>

                <!-- Academic Results -->
                <div>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-dark">
                                    <th class="border p-2">Course Code</th>
                                    <th class="border p-2">Course Title</th>
                                    <th class="border p-2">Units</th>
                                    <th class="border p-2">Status</th>
                                    <th class="border p-2">Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $result)
                                    <tr class="border">
                                        <td class="border p-2">{{ $result->course->course_code ?? ($result->code ?? 'N/A') }}</td>
                                        <td class="border p-2">{{ $result->course->course_title ?? 'N/A' }}</td>
                                        <td class="border p-2">{{ $result->c_unit ?? ($result->cunit ?? 'N/A') }}</td>

                                        <td class="border p-2">{{ $result->status ?? ($result->cstatus ?? 'N/A') }}
                                        </td>
                                        <td class="border p-2">{{ $result->score }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <p class="text-center bold"><strong>Cumulative Grade Point Average (CGPA / WA) Score for the Degree
                                of Master is</strong> <input type="number" name="cgpa" value="{{$cgpa ?? 'N/A'}}"
                                class="border  p-1 px-2 w-full rounded-md"></p>
                        <div class="test">
                            <div>
                                <label class="font-semibold block">Degree Awarded:</label>
                                <input type="text" name="degreeAward" value="{{ $degreeAwarded ?? 'Not Specified' }}"
                                    class="border  p-1 px-2 w-full rounded-md">
                            </div>
                            <div>
                                <label class="font-semibold block">Date of Award:</label>

                                <input type="text" name="awardDate"
                                    value="{{ optional($results->first())->effectivedate ? \Carbon\Carbon::parse($results->first()->effectivedate)->format('d F, Y') : 'N/A' }}"
                                    class="border p-1 px-2 w-full rounded-md">

                            </div>


                        </div>
                        <p><strong>Area of Specialization:</strong>
                            {{ $biodata->feildofinterest ?? ($results->first()->specialization->field_title ?? 'N/A') }}
                        </p>

                    </div>
                </div>

                <!-- Address Section -->

              <div class="address-section">
                    <h3 class="text-lg font-semibold mb-3">Delivery Addresses</h3>

                    <div class="address-field">
                        <label for="ecopyAddress">E-Copy Address:</label>
                        <textarea
                            id="ecopyAddress"
                            name="ecopyAddress"
                            placeholder="Enter e-copy delivery address">{{ $biodata->ecopy_address ?? '' }}</textarea>
                    </div>

                    @if ($biodata && $biodata->couriers && $biodata->couriers->count() > 0)
                        <div class="mt-3">
                            <h4 class="font-semibold mb-2">Courier Addresses:</h4>
                            @foreach ($biodata->couriers as $index => $courier)
                                <div class="courier-item">
                                    <label for="courierAddress{{ $index }}">
                                        Courier Address {{ $index + 1 }}
                                        @if($courier->name)
                                            ({{ $courier->name }})
                                        @endif
                                    </label>
                                    <textarea
                                        id="courierAddress{{ $index }}"
                                        name="courierAddresses[{{ $courier->id }}]"
                                        class="courier-address-field"
                                        data-courier-id="{{ $courier->id }}"
                                        placeholder="Enter courier delivery address">{{ $courier->address ?? '' }}</textarea>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>


                <form action="{{ route('admin.transcriptSubmit') }}" method="POST" onsubmit="return validateForm()">
                    @csrf
                    @if ($biodata)
                        <input type="hidden" name="matric" value="{{ $biodata->matric }}">
                        <input type="hidden" name="invoiceNo" value="{{ $biodata->invoiceno}}">

                    @endif

                    <input type="hidden" name="secAdmin"
                        value="{{ optional($biodata)->sessionadmin ?? (optional($results->first())->sec ?? optional($results->first())->yr_of_entry) }}">

                    <input type="hidden" name="cgpa" id="cgpaInput">
                    <input type="hidden" name="degreeAward" id="degreeAwardInput">
                    <input type="hidden" name="awardDate" id="awardDateInput">
                    <input type="hidden" name="ecopyAddress" id="ecopyAddressInput">
                    <div id="courierAddressesContainer"></div>


                    <div class="btn-approve mt-4">
                        <button type="submit" class="btn-primary text-white px-4 py-2 rounded-md ">
                            Submit for Approval
                        </button>
                    </div>
                </form>

                <script>
                    function validateForm() {
                        let cgpa = document.querySelector("input[name='cgpa']").value.trim();
                        let degreeAward = document.querySelector("input[name='degreeAward']").value.trim();
                        let awardDate = document.querySelector("input[name='awardDate']").value.trim();

                        let submitButton = document.querySelector('.btn-approve button');
                        if (cgpa === "" || degreeAward === "" || awardDate === "") {
                            alert("Please fill in both CGPA and Degree Award fields before submitting.");
                            return false;
                        }

                        submitButton.disabled = true;

                        submitButton.innerHTML =
                            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Submitting...`;

                        document.getElementById("cgpaInput").value = cgpa;
                        document.getElementById("degreeAwardInput").value = degreeAward;
                        document.getElementById("awardDateInput").value = awardDate;
                        document.getElementById("ecopyAddressInput").value = document.getElementById("ecopyAddress").value.trim();

                        // Collect all courier addresses
                        const courierAddressFields = document.querySelectorAll('.courier-address-field, textarea[name^="courierAddresses"]');
                        const container = document.getElementById('courierAddressesContainer');
                        container.innerHTML = '';

                        courierAddressFields.forEach((field) => {
                            const courierId = field.dataset.courierId || field.name.match(/\[([^\]]+)\]/)?.[1];
                            const address = field.value.trim();
                            
                            if (courierId) {
                                const hiddenInput = document.createElement('input');
                                hiddenInput.type = 'hidden';
                                hiddenInput.name = `courierAddresses[${courierId}]`;
                                hiddenInput.value = address;
                                container.appendChild(hiddenInput);
                            }
                        });


                        return true;
                    }
                </script>


                <script>
                    document.querySelector("form").addEventListener("submit", function() {
                        document.getElementById("cgpaInput").value = document.querySelector("input[name='cgpa']").value;
                        document.getElementById("degreeAwardInput").value = document.querySelector("input[name='degreeAward']")
                            .value;
                    });
                </script>


            </div>


        </div>

    </div>


</x-admin-layout>
