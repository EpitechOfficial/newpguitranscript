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

                @if ($results->isNotEmpty())

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
                                            <td class="border p-2">
                                                {{ $result->course->course_code ?? ($result->code ?? 'N/A') }}</td>
                                            <td class="border p-2">{{ $result->course->course_title ?? 'N/A' }}</td>
                                            <td class="border p-2">{{ $result->c_unit ?? ($result->cunit ?? 'N/A') }}
                                            </td>

                                            <td class="border p-2">{{ $result->status ?? ($result->cstatus ?? 'N/A') }}
                                            </td>
                                            <td class="border p-2">{{ $result->score }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <hr>

                            <div class="test">
                                <div>
                                    <label class="font-semibold block">Degree Awarded:</label>
                                    <input type="text" name="degreeAward"
                                        value="{{ $degreeAwarded ?? (in_array($biodata->degree, ['Ph.D', 'P.hd']) ?? ('Doctor of Philosophy' ?? (in_array($biodata->degree, ['M.Phil', 'M.phil']) ?? ('Doctor of Philosophy' ?? 'Not Specified')))) }}"
                                        class="border  p-1 px-2 w-full rounded-md">
                                </div>
                                <div>
                                    <label class="font-semibold block">CGPA:</label>
                                    <input type="number" name="cgpa" value="{{ $cgpa ?? '0' }}"
                                        class="border  p-1 px-2 w-full rounded-md">
                                </div>
                                <div>
                                    <strong><label class="font-semibold block">Date of Award:</label></strong>

                                    <input type="text" name="awardDate"
                                        value="{{ optional($dateAward) ? \Carbon\Carbon::parse($dateAward)->format('d F, Y') : 'N/A' }}"
                                        class="border p-1 px-2 w-full rounded-md">

                                </div>


                            </div>
                            <strong>
                                <p class="bold" style="text-align:center; margin-bottom:0">Thesis Title:</p>
                            </strong>
                            <div style="display: flex; justify-content: center;">

                                <textarea name="thesisTitle" placeholder="Enter Thesis Title" class="border p-1 px-2 w-full rounded-md" cols="50">
                        {{ $thesisTitle }}
                        </textarea>


                            </div>

                        </div>
                    </div>
                @else
                    <div class="test">
                        <div>
                            <strong><label class="font-semibold block">Degree Awarded:</label></strong>
                            <input type="text" name="degreeAward"
                                value="{{ in_array(strtoupper($biodata->degree ?? ''), ['PH.D', 'PHD']) ? 'Doctor of Philosophy' : (in_array(strtoupper($biodata->degree ?? ''), ['M.PHIL']) ? 'Master of Philosophy' : 'Not Specified') }}"
                                class="border p-1 px-2 w-full rounded-md">
                        </div>
                        <div>

                            <input type="hidden" name="cgpa" value="{{ $biodata->award ?? '0' }}">
                        </div>
                        <div>
                            <strong><label class="font-semibold block">Date of Award:</label></strong>

                            <input type="text" name="awardDate"
                                value="{{ $dateAward && \Carbon\Carbon::canBeCreatedFromFormat($dateAward, 'Y-m-d')
                                    ? \Carbon\Carbon::parse($dateAward)->format('d F, Y')
                                    : 'Not Specified' }}"
                                class="border p-1 px-2 w-full rounded-md">

                        </div>


                    </div>

                    <strong>
                        <p class="bold" style="text-align:center; margin-bottom:0">Thesis Title:</p>
                    </strong>
                    <div style="display: flex; justify-content: center;">

                        <textarea name="thesisTitle" placeholder="Enter Thesis Title" class="border p-1 px-2 w-full rounded-md" cols="50">
                        {{ $thesisTitle }}
                        </textarea>


                    </div>




                @endif


                <form action="{{ route('admin.transcriptSubmitHigher') }}" method="POST"
                    onsubmit="return validateForm()">
                    @csrf
                    @if ($biodata)
                        <input type="hidden" name="matric" value="{{ $biodata->matric }}">
                        <input type="hidden" name="invoiceNo" value="{{ $biodata->email }}">
                    @endif

                    <input type="hidden" name="secAdmin"
                        value="{{ optional($biodata)->sessionadmin ?? (optional($results->first())->sec ?? optional($results->first())->yr_of_entry) }}">

                    <input type="hidden" name="cgpa" id="cgpaInput">
                    <input type="hidden" name="degreeAward" id="degreeAwardInput">
                    <input type="hidden" name="awardDate" id="awardDateInput">
                    <input type="hidden" name="thesisTitle" id="thesisTitleInput">


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
                        let thesisTitle = document.querySelector("textarea[name='thesisTitle']").value.trim();

                        let submitButton = document.querySelector('.btn-approve button');
                        if (thesisTitle === "" || degreeAward === "" || awardDate === "") {
                            alert("Please fill all required fields before submitting.");
                            return false;
                        }

                        submitButton.disabled = true;

                        submitButton.innerHTML =
                            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Submitting...`;

                        document.getElementById("cgpaInput").value = cgpa;
                        document.getElementById("thesisTitleInput").value = thesisTitle;
                        document.getElementById("degreeAwardInput").value = degreeAward;
                        document.getElementById("awardDateInput").value = awardDate;

                        return true;
                    }
                </script>


                <script>
                    document.querySelector("form").addEventListener("submit", function() {
                        document.getElementById("cgpaInput").value = document.querySelector("input[name='cgpa']").value;
                        document.getElementById("thesisTitleInput").value = document.querySelector(
                                "textarea[name='thesisTitle']")
                            .value;
                        document.getElementById("degreeAwardInput").value = document.querySelector("input[name='degreeAward']")
                            .value;
                    });
                </script>


            </div>


        </div>

    </div>


</x-admin-layout>
