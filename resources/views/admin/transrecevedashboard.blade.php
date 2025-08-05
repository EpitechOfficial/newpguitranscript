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

        .alert {
            z-index: 9999 !important;
            margin-top: 5rem !important;
            background: #198754 !important;
            color: #fff !important;
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
        @if (session('success'))
            <div id="success-alert"
                class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow-lg"
                role="alert"
                style="opacity: 0; transform: translateX(100%); transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out; z-index: 1050;">
                <strong>Success:</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif



        <!-- Content Start -->
        <div class="card mb-2">
            <div class="card-body h-100">
                {{-- @dd(session('user')); --}}
                Welcome <h1> {{ session('admin_user')->fullname }}
                </h1>
            </div>
        </div>
        <!-- Content End -->
        <div class="card mb-2 w-100">
            @if ($records->isEmpty())
                <div class="card cardEmpty mb-2">
                    <p class="empty">No Record found</p>


                </div>
            @else
                <div class="card-body h-100 w-100">
                    <div class="program">
                        <h1 class="mb-0 pb-0 display-4" id="title">Transcript Request</h1>
                    </div>
                    <br>
                    <nav class="breadcrumb-container w-100 d-inline-block" aria-label="breadcrumb">
                        <div class="table-responsive">
                            <table id="recordsTable" class="table table-bordered table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>S/N</th>
                                        <th>Matric</th>
                                        <th>Name</th>
                                        <th>Faculty</th>
                                        <th>Department</th>
                                        <th>Degree</th>
                                        <th>Field of Interest</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($records as $index => $record)
                                        @php
                                            $status = [
                                                0 => 'Fresh Application',
                                                7 => 'Record Officer',
                                                2 => 'Transcript Officer',
                                                3 => 'Key in Officer',
                                                4 => 'Processing Officer',
                                                5 => 'Filing Officer',
                                                6 => 'Help Desk',
                                                8 => 'Complete Transacript Process',
                                                9 => 'Dispatch to Student',
                                            ];
                                        @endphp

                                        @if ($record->status == 0)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $record->matric }}</td>
                                                <td>{{ $record->Surname . ' ' . $record->Othernames }}</td>
                                                <td>{{ $record->faculty }}</td>
                                                <td>{{ $record->department }}</td>
                                                <td>{{ $record->degree }}</td>
                                                <td>{{ $record->feildofinterest }}</td>
                                                <td>{{ $status[$record->status] ?? 'Unknown Status' }}</td>
                                                <td>
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        {{-- <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#viewModal" data-matric="{{ $record->matric }}"
                                                        data-name="{{ $record->Surname . ' ' . $record->Othernames }}"
                                                        data-faculty="{{ $record->faculty }}"
                                                        data-department="{{ $record->department }}"
                                                        data-degree="{{ $record->degree }}"
                                                        data-fieldofinterest="{{ $record->feildofinterest }}"
                                                        data-purpose="{{ $record->transInvoice->purpose }}"
                                                        data-amount="{{ $record->transInvoice->dy }}"
                                                        data-sec="{{ $record->sessionadmin }}"
                                                        data-copies="{{ $record->transInvoice->mth }}">
                                                        View
                                                    </button> --}}
                                                        <a class="btn btn-primary btn-sm"
                                                            href="{{ route('admin.transcript.details', ['id' => $record->email]) }}"
                                                            target="_blank">View</a>

                                                        @if ($record->file && $record->file->file_path)
                                                            <button class="btn btn-warning btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#viewDocumentModal"
                                                                data-filepath="{{ asset($record->file->file_path) }}"
                                                                data-award="{{ $record->file->file_path ?? '' }}">
                                                                View NOR
                                                            </button>
                                                        @else
                                                            <button class="btn btn-danger btn-sm" disabled>
                                                                No NOR
                                                            </button>
                                                        @endif

                                                        {{-- <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#viewDocumentModal"
                                                        data-award="{{ $record->file->file_path }}">
                                                        View NOR
                                                    </button> --}}

                                                        {{-- {{ dd($record) }} --}}

                                                        <select class="form-select form-select-sm w-auto text-nowrap"
                                                            data-matric="{{ $record->matric }}"
                                                            data-invoiceno="{{ $record->transInvoice->invoiceno ?? '' }}"
                                                            onchange="updateCheque(this)">
                                                            <option value="">Select Status</option>
                                                            <option value="7">Record Officer</option>
                                                            <option value="2">Transcript Officer</option>
                                                            <option value="3">Key in Officer</option>
                                                            <option value="4">Processing Officer</option>
                                                            <option value="5">Filing Officer</option>
                                                            <option value="6">Help Desk</option>
                                                            <option value="8">Complete Transacript Process</option>
                                                            <option value="9">Dispatch to Student</option>
                                                        </select>

                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>



                        </div>
                    </nav>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="modal-title" id="viewModalLabel"><strong> Student Transcript Details
                                    </strong></h2>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Matric:</strong> <span id="modal-matric"></span></p>
                                <p><strong>Name:</strong> <span id="modal-name"></span></p>
                                <p><strong>Faculty:</strong> <span id="modal-faculty"></span></p>
                                <p><strong>Department:</strong> <span id="modal-department"></span></p>
                                <p><strong>Degree:</strong> <span id="modal-degree"></span></p>
                                <p><strong>Field of Interest:</strong> <span id="modal-fieldofinterest"></span></p>
                                <p><strong>Session Admitted:</strong> <span id="modal-sec"></span></p>
                                <p><strong>Purpose:</strong> <span id="modal-purpose"></span></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="viewDocumentModal" tabindex="-1" aria-labelledby="viewDocumentModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewDocumentModalLabel">View Uploaded Document</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Placeholder for the document -->
                                <div id="documentContainer" class="text-center">
                                    <p>Loading...</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    let baseURL = "{{ url('/') }}";

                    function updateCheque(selectElement) {
                        var selectedValue = selectElement.value;
                        var matric = selectElement.getAttribute("data-matric");
                        var invoiceno = selectElement.getAttribute("data-invoiceno");

                        if (!selectedValue) {
                            alert('Please select Status value.');
                            return; // Stop if no option is selected
                        }

                        console.log('Selected Status value:', selectedValue); // Debugging line

                        fetch(baseURL + "/admin/update-cheque", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    matric: matric,
                                    invoiceno: invoiceno,
                                    cheque: selectedValue // Sending the correct field here
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    // If response is not OK (like 422, 500, etc)
                                    return response.json().then(err => {
                                        console.error('Server responded with error:', err);
                                        throw new Error('Server error');
                                    });
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    alert('Status updated successfully.');
                                    // If record is no longer supposed to be visible (status != 0), remove it from the table
                                    if (selectedValue != 0) {
                                        const row = selectElement.closest('tr');
                                        if (row) {
                                            //  row.remove();
                                            row.style.transition = 'opacity 0.5s ease';
                                            row.style.opacity = 0;
                                            setTimeout(() => row.remove(), 500);

                                        }
                                    }
                                    // Optionally reload or update your page/UI
                                } else {
                                    alert('Failed to update Status.');
                                }
                            })
                            .catch(error => {
                                console.error('Fetch error:', error); // log it clearly
                                alert('Something went wrong. Check console for details.');
                            });
                    }
                </script>







                <script>
                    const viewModal = document.getElementById('viewModal');
                    viewModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;

                        // Populate basic details
                        document.getElementById('modal-matric').textContent = button.getAttribute('data-matric');
                        document.getElementById('modal-name').textContent = button.getAttribute('data-name');
                        document.getElementById('modal-faculty').textContent = button.getAttribute('data-faculty');
                        document.getElementById('modal-department').textContent = button.getAttribute('data-department');
                        document.getElementById('modal-degree').textContent = button.getAttribute('data-degree');
                        document.getElementById('modal-sec').textContent = button.getAttribute('data-sec');
                        document.getElementById('modal-fieldofinterest').textContent = button.getAttribute(
                            'data-fieldofinterest');

                        // Handle purpose formatting
                        const copies = button.getAttribute('data-copies').split(',').map(item => item.trim());
                        const purposes = button.getAttribute('data-purpose').split(',').map(item => item.trim());
                        const amounts = button.getAttribute('data-amount').split(',').map(item => item.trim());

                        // Format the text as "X copies of Y which cost Z"
                        let purposeText = '';
                        for (let i = 0; i < purposes.length - 1; i++) {
                            purposeText +=
                                `${copies[i] || 'N/A'} copies of ${purposes[i] || 'N/A'} which cost ${amounts[i]  || 'N/A'},`;
                            if (i < purposes.length - 1) {
                                purposeText += '<br>'; // Add line breaks for clarity
                            }
                        }
                        document.getElementById('modal-purpose').innerHTML = purposeText;
                    });
                </script>


                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const viewDocumentModal = document.getElementById('viewDocumentModal');
                        viewDocumentModal.addEventListener('show.bs.modal', function(event) {
                            const button = event.relatedTarget;
                            const awardLink = button.getAttribute('data-award');
                            const documentContainer = document.getElementById('documentContainer');

                            if (awardLink) {
                                // Create the full URL using asset helper from Laravel
                                const fileUrl =
                                `{{ config('app.url') }}/storage/${awardLink}`; // Directly access the public storage path

                                console.log("Generated file URL:", fileUrl); // Log the file URL for debugging

                                const fileExtension = awardLink.split('.').pop().toLowerCase();
                                let content = '';

                                // Handle different file types (image and PDF)
                                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                                    // Display image
                                    content = `<img src="${fileUrl}" alt="Document" class="img-fluid">`;
                                } else if (fileExtension === 'pdf') {
                                    // Display PDF
                                    content =
                                        `<iframe src="${fileUrl}" width="100%" height="500px" frameborder="0"></iframe>`;
                                } else {
                                    // Unsupported file type
                                    content = `<p class="text-danger">Unable to display this file type.</p>`;
                                }

                                documentContainer.innerHTML = content;
                            } else {
                                documentContainer.innerHTML = `<p class="text-danger">No document available.</p>`;
                            }
                        });
                    });

                    function processRecord(button, matric, sessionadmin) {
                        console.log("Processing record for:", matric, sessionadmin); // Debugging log

                        button.disabled = true;
                        button.innerHTML =
                            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...`;
                        const url = '{{ route('admin.processRecord') }}'; // Named route for the backend action

                        // Create a form dynamically to submit via POST
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;

                        // Add CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);

                        // Add matric and sessionadmin values
                        const matricInput = document.createElement('input');
                        matricInput.type = 'hidden';
                        matricInput.name = 'matric';
                        matricInput.value = matric;
                        form.appendChild(matricInput);

                        const sessionAdminInput = document.createElement('input');
                        sessionAdminInput.type = 'hidden';
                        sessionAdminInput.name = 'sessionadmin';
                        sessionAdminInput.value = sessionadmin;
                        form.appendChild(sessionAdminInput);

                        console.log("Submitting form to:", url); // Debugging log

                        // Append form to the body and submit it
                        document.body.appendChild(form);
                        form.submit();


                    }

                    window.addEventListener("pageshow", function() {
                        document.querySelectorAll(".btn-success").forEach((button) => {
                            button.disabled = false;
                            button.innerHTML = "Process";
                        });
                    });
                </script>



            @endif
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#recordsTable').DataTable({
                responsive: true, // Enables responsive layout
                paging: true, // Enables pagination
                searching: true, // Enables searching
                ordering: true // Enables column sorting
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let alert = document.getElementById('success-alert');

            if (alert) {
                // Fade in and Slide in from right
                setTimeout(() => {
                    alert.style.opacity = 1;
                    alert.style.transform = 'translateX(0)';
                }, 100); // Slight delay for smooth effect

                // Fade out and Slide out to the right after 5 seconds
                setTimeout(() => {
                    alert.style.opacity = 0;
                    alert.style.transform = 'translateX(100%)';
                    setTimeout(() => alert.remove(), 500); // Remove after fade-out and slide-out
                }, 5000);
            }
        });
    </script>




</x-admin-layout>
