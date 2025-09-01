<x-admin-layout :pageName="'Students By Department'">

    @push('styles')
        <!-- jQuery (required for DataTables) -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

        <!-- DataTables CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" type="text/css"
            href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

        <!-- Bootstrap CSS (if not already included) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @endpush
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
        #studentsTable {
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
        }

        #studentsTable thead {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center !important;
        }

        #studentsTable tbody tr {
            transition: background-color 0.3s ease;
        }

        #studentsTable tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Column Separator */
        #studentsTable th,
        #studentsTable td {
            border-right: 1px solid #ddd;
        }

        #studentsTable th:last-child,
        #studentsTable td:last-child {
            border-right: none;
        }

        /* Styling for buttons */
        .btn {
            border-radius: 5px;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        /* Hover effect for buttons */
        .btn-primary:hover {
            background-color: #007bff;
            border-color: #007bff;
        }

        .spinner {
            display: none;
        }

        /* Responsive table */
        @media (max-width: 768px) {

            #studentsTable td,
            #studentsTable th {
                font-size: 0.85rem;
            }
        }

        .alert {
            z-index: 9999 !important;
            margin-top: 5rem !important;
            background: #198754 !important;
            color: #fff !important;
        }
    </style>
    <!-- SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title">
            <div class="row w-full w-100">
                <!-- Title Start -->
                <div class="">
                    <h1 class="mb-0 pb-0 display-4" id="title">Students By Department</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    </nav>
                </div>
                <!-- Title End -->
            </div>
        </div>

        <!-- Content Start -->
        <div class="card mb-2">
            <div class="card-body h-100">
                Welcome <h1> {{ session('admin_user')->fullname }}</h1>
            </div>
        </div>

        <div class="card mb-2">
            <div class="card-body">
                <div class="program">
                    <h1 class="mb-0 pb-0 display-4" id="title">Student List</h1>
                </div>
                <br>
                <div class="mb-3 text-center">
                    <select id="departmentSelect" class="form-control d-inline-block w-25">
                        <option value="">Select Department</option>
                    </select>
                    <button id="fetchBtn" class="btn btn-primary ms-2" disabled>Fetch Students</button>
                    <span class="spinner" id="fetchSpinner">Loading...</span>
                </div>
                <div id="studentsContainer"></div>
            </div>
        </div>
    </div>
    <script>
        // Function to check if department is selected and enable/disable fetch button
        function checkFormValidity() {
            const department = document.getElementById('departmentSelect').value;
            const fetchBtn = document.getElementById('fetchBtn');

            if (department) {
                fetchBtn.disabled = false;
                fetchBtn.classList.remove('btn-secondary');
                fetchBtn.classList.add('btn-primary');
            } else {
                fetchBtn.disabled = true;
                fetchBtn.classList.remove('btn-primary');
                fetchBtn.classList.add('btn-secondary');
            }
        }

        // Fetch departments on page load
        fetch('{{ route('admin.students_by_department.fetch_departments') }}')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('departmentSelect');
                if (data.departments) {
            // Clear existing options except the first one
            select.innerHTML = '<option value="">Select Department</option>';
            
            // Sort departments by name
            Object.entries(data.departments)
                .sort((a, b) => a[1].localeCompare(b[1]))
                .forEach(([id, name]) => {
                    select.innerHTML += `<option value="${id}">${name}</option>`;
                });
        }
                checkFormValidity();
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error fetching departments.',
                    confirmButtonColor: '#d33'
                });
            });

        // Handle department selection change
        document.getElementById('departmentSelect').addEventListener('change', function() {
            checkFormValidity();
        });

        // Handle fetch button click
        let dataTable = null;
document.getElementById('fetchBtn').onclick = function() {
    const department = document.getElementById('departmentSelect').value;
    
    if (!department) {
        Swal.fire({
            icon: 'warning',
            title: 'Warning',
            text: 'Please select a department first.',
            confirmButtonColor: '#ffc107'
        });
        return;
    }
    
    document.getElementById('fetchSpinner').style.display = 'inline';
    
    fetch(`{{ route('admin.students_by_department.fetch_students') }}?department=${encodeURIComponent(department)}`)
        .then(response => {
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return response.text().then(text => {
                console.log('Raw response:', text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('JSON parse error:', e);
                    console.error('Response text:', text);
                    throw new Error('Invalid JSON response from server');
                }
            });
        })
        .then(data => {
            document.getElementById('fetchSpinner').style.display = 'none';
            console.log('Parsed data:', data);
            
            if (data.error) {
                console.error('Server error:', data.error);
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: data.error,
                    confirmButtonColor: '#d33'
                });
                return;
            }
            
            if (data.students && data.students.length > 0) {
                let html = `<div class="table-responsive">
                    <table id="studentsTable" class="table table-bordered table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>S/N</th>
                                <th>Matric Number</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Session</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>`;
                
                data.students.forEach((student, index) => {
                    const safeMatric = (student.matric || '').toString();
                    const safeName = (student.name || 'Name not found').replace(/'/g, '&#39;');
                    const safeDepartment = (student.department || 'Unknown Department').replace(/'/g, '&#39;');
                    const safeSession = (student.session || 'Unknown Session').toString();
                    
                    html += `<tr>
                        <td>${index + 1}</td>
                        <td>${safeMatric}</td>
                        <td>${safeName}</td>
                        <td>${safeDepartment}</td>
                        <td>${safeSession}</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn btn-success btn-sm w-auto text-nowrap"
                                    data-matric="${safeMatric}"
                                    data-sessionadmin="${safeSession}"
                                    onclick="processTranscript(this,'${safeMatric}', '${safeSession}')">
                                    View Transcript
                                </button>
                            </div>
                        </td>
                    </tr>`;
                });
                
                html += `</tbody></table></div>`;
                document.getElementById('studentsContainer').innerHTML = html;
                
                // Check if DataTables is available before initializing
                if (typeof $.fn.DataTable !== 'undefined') {
                    // Destroy existing DataTable if it exists
                    if (typeof dataTable !== 'undefined' && dataTable) {
                        dataTable.destroy();
                    }
                    
                    // Initialize new DataTable
                    dataTable = $('#studentsTable').DataTable({
                        responsive: true,
                        paging: true,
                        searching: true,
                        ordering: true,
                        pageLength: 25,
                        language: {
                            search: "Search students:",
                            lengthMenu: "Show _MENU_ students per page",
                            info: "Showing _START_ to _END_ of _TOTAL_ students",
                            paginate: {
                                first: "First",
                                last: "Last",
                                next: "Next",
                                previous: "Previous"
                            }
                        }
                    });
                    
                    console.log(`Successfully loaded ${data.students.length} students with DataTable`);
                } else {
                    console.warn('DataTables not available, displaying as regular table');
                    console.log(`Successfully loaded ${data.students.length} students without DataTable`);
                }
                
            } else {
                document.getElementById('studentsContainer').innerHTML = 
                    '<div class="cardEmpty"><p class="empty">No students found in this department</p></div>';
                console.log('No students found for department:', department);
            }
        })
        .catch(error => {
            document.getElementById('fetchSpinner').style.display = 'none';
            document.getElementById('studentsContainer').innerHTML = '';
            
            console.error('Fetch error:', error);
            console.error('Error stack:', error.stack);
            
            Swal.fire({
                icon: 'error',
                title: 'Network Error',
                text: `Error fetching student data: ${error.message}`,
                confirmButtonColor: '#d33'
            });
        });
};
    </script>

    <script>
        function processTranscript(button, matric, sessionadmin) {
            console.log("Processing record for:", matric, sessionadmin); // Debugging log

            const url = '{{ route('admin.students_by_department.view_transcript') }}'; // Named route for the backend action

            // Create a form dynamically to submit via POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.target = '_blank';

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
                button.innerHTML = "View Transcript";
            });
        });
    </script>


    @push('scripts')
        <!-- Bootstrap JS (if not already included) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- DataTables JS -->
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js">
        </script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js">
        </script>
        <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

        <!-- SweetAlert2 (if not already included) -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // Check if all required libraries are loaded
            $(document).ready(function() {
                console.log('jQuery version:', $.fn.jquery);
                console.log('DataTables available:', typeof $.fn.DataTable !== 'undefined');
                console.log('SweetAlert2 available:', typeof Swal !== 'undefined');

                if (typeof $.fn.DataTable === 'undefined') {
                    console.error('DataTables is not loaded!');
                }
            });
        </script>
    @endpush
</x-admin-layout>
