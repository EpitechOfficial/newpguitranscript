<x-admin-layout :pageName="'Processed Transcript Page'">
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
                    <h1 class="mb-0 pb-0 display-4" id="title">Processed Transcript Page</h1>
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



        <div class="card mb-2 w-100">
            @if ($records->isEmpty())
                <div class="card cardEmpty mb-2">
                    <p class="empty">No Record found</p>


                </div>
            @else
                <div class="card-body h-100 w-100">
                    <div class="program">
                        <h1 class="mb-0 pb-0 display-4" id="title">Transcript Records</h1>
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $index => $record)
                                        @if ($record->status == 7)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $record->matric }}</td>
                                                <td>{{ $record->Surname . ' ' . $record->Othernames }}</td>
                                                <td>{{ $record->faculty }}</td>
                                                <td>{{ $record->department }}</td>
                                                <td>{{ $record->degree }}</td>
                                                <td>{{ $record->feildofinterest }}</td>
                                                <td>
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <button class="btn btn-primary btn-sm w-auto text-nowrap"
                                                            data-matric="{{ $record->matric }}"
                                                            data-sessionadmin="{{ $record->sessionadmin }}"
                                                            onclick="processTranscript(this,'{{ $record->matric }}', '{{ $record->sessionadmin }}', '{{ $record->email }}')">
                                                            View Transcript
                                                        </button>


                                                        <button class="btn btn-success btn-sm"
                                                            data-matric="{{ $record->matric }}"
                                                            data-sessionadmin="{{ $record->sessionadmin }}"
                                                            onclick="approveRecord(this,'{{ $record->matric }}', '{{ $record->sessionadmin }}','{{ $record->ecopy_email }}')">
                                                            Approve
                                                        </button>
                                                        <button class="btn btn-danger btn-sm"
                                                            data-matric="{{ $record->matric }}"
                                                            data-sessionadmin="{{ $record->sessionadmin }}"
                                                            onclick="rejectRecord(this,'{{ $record->matric }}', '{{ $record->sessionadmin }}')">
                                                            Reject
                                                        </button>

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
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>


                <script>
                    function processTranscript(button, matric, sessionadmin, invoiceNo) {
                        console.log("Processing record for:", matric, sessionadmin); // Debugging log

                        button.disabled = true;
                        button.innerHTML =
                            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...`;
                        const url = '{{ route('admin.processTranscript') }}'; // Named route for the backend action

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

                        const invoiceNoInput = document.createElement('input');
                        invoiceNoInput.type = 'hidden';
                        invoiceNoInput.name = 'invoiceNo';
                        invoiceNoInput.value = invoiceNo;
                        form.appendChild(invoiceNoInput);

                        console.log("Submitting form to:", url); // Debugging log

                        // Append form to the body and submit it
                        document.body.appendChild(form);
                        form.submit();


                    }

                    // window.addEventListener("pageshow", function() {
                    //     document.querySelectorAll(".btn-success").forEach((button) => {
                    //         button.disabled = false;
                    //         button.innerHTML = "View Transcript";
                    //     });
                    // });


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
                    function approveRecord(button, matric, sessionadmin, ecopy_email) {
                        console.log("Processing record for:", matric, sessionadmin);

                        const confirmationText = ecopy_email ?
                            `Are you sure you want to approve the transcript for student ${matric}? \n Note: An email will be sent to ${ecopy_email} with the transcript.` :
                            `Are you sure you want to approve the transcript for student ${matric}?`;
                        // Show SweetAlert confirmation dialog
                        Swal.fire({
                            title: 'Confirm Approval',
                            text: confirmationText,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Yes, approve it!',
                            cancelButtonText: 'Cancel',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // User confirmed, proceed with approval
                                button.disabled = true;
                                button.innerHTML =
                                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...`;

                                const url = '{{ route('admin.transcriptApprove') }}';

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

                                console.log("Submitting form to:", url);

                                // Append form to the body and submit it
                                document.body.appendChild(form);
                                form.submit();

                                // Show success message (optional)
                                Swal.fire({
                                    title: 'Processing...',
                                    text: 'Transcript approval is being processed.',
                                    icon: 'info',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                            // If user cancels, do nothing - button remains enabled
                        });
                    }

                    function rejectRecord(button, matric, sessionadmin) {
                        console.log("Processing record for:", matric, sessionadmin);

                        // Show SweetAlert confirmation dialog for rejection
                        Swal.fire({
                            title: 'Confirm Rejection',
                            text: `Are you sure you want to reject the transcript for student ${matric}?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Yes, reject it!',
                            cancelButtonText: 'Cancel',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // User confirmed, proceed with rejection
                                button.disabled = true;
                                button.innerHTML =
                                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...`;

                                const url = '{{ route('admin.transcriptReject') }}';

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

                                console.log("Submitting form to:", url);

                                // Append form to the body and submit it
                                document.body.appendChild(form);
                                form.submit();

                                // Show processing message (optional)
                                Swal.fire({
                                    title: 'Processing...',
                                    text: 'Transcript rejection is being processed.',
                                    icon: 'info',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                            // If user cancels, do nothing - button remains enabled
                        });
                    }

                    // window.addEventListener("pageshow", function() {
                    //     document.querySelectorAll(".btn-success").forEach((button) => {
                    //         button.disabled = false;
                    //         button.innerHTML = "A";
                    //     });
                    // });

                    function processRecord(button, matric, sessionadmin, sessiongrad) {
                        console.log("Processing record for:", matric, sessionadmin, sessiongrad); // Debugging log

                        button.disabled = true;
                        button.innerHTML =
                            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...`;
                        const url = '{{ route('admin.processView') }}'; // Named route for the backend action

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

                        const sessiongradInput = document.createElement('input');
                        sessiongradInput.type = 'hidden';
                        sessiongradInput.name = 'sessiongrad';
                        sessiongradInput.value = sessiongrad;
                        form.appendChild(sessiongradInput);

                        console.log("Submitting form to:", url); // Debugging log

                        // Append form to the body and submit it
                        document.body.appendChild(form);
                        form.submit();


                    }
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
