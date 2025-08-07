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
    <!-- SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>


@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Upload ResultOld Records (Excel)</h2>

    {{-- @if(session('success'))
        <div class="alert alert-success text-center">
            <pre>{{ session('success') }}</pre>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif --}}

    {{-- @if(session('import_summary'))
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Import Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-primary">{{ session('import_summary.total_rows') }}</h4>
                            <p class="text-muted">Total Rows</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-success">{{ session('import_summary.success_count') }}</h4>
                            <p class="text-muted">Successfully Imported</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-warning">{{ session('import_summary.duplicate_count') }}</h4>
                            <p class="text-muted">Duplicates Found</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-danger">{{ session('import_summary.error_count') }}</h4>
                            <p class="text-muted">Errors</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('import_duplicates') && count(session('import_duplicates')) > 0)
        <div class="card mt-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Duplicate Records Found</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-warning">
                            <tr>
                                <th>Row</th>
                                <th>Matric No</th>
                                <th>Course Code</th>
                                <th>Status</th>
                                <th>Score</th>
                                <th>Session</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(session('import_duplicates') as $duplicate)
                                <tr>
                                    <td>{{ $duplicate['row'] }}</td>
                                    <td>{{ $duplicate['matno'] }}</td>
                                    <td>{{ $duplicate['code'] }}</td>
                                    <td>{{ $duplicate['status'] }}</td>
                                    <td>{{ $duplicate['score'] }}</td>
                                    <td>{{ $duplicate['sec'] }}</td>
                                    <td>{{ $duplicate['dept'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if(session('import_errors') && count(session('import_errors')) > 0)
        <div class="card mt-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Import Errors</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-danger">
                            <tr>
                                <th>Row</th>
                                <th>Error Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(session('import_errors') as $error)
                                <tr>
                                    <td>{{ explode(':', $error)[0] ?? 'N/A' }}</td>
                                    <td>{{ $error }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif --}}

    <form action="{{ route('result_old.upload_excel') }}" method="POST" enctype="multipart/form-data" class="text-center" id="uploadForm">
        @csrf
        <div class="mb-3">
            <input type="file" name="file" class="form-control w-50 mx-auto" id="fileInput" accept=".xls,.xlsx,.csv">
            @error('file')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary" id="uploadBtn">Upload Excel</button>
    </form>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];
            
            if (!file) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No File Selected',
                    text: 'Please select a file to upload.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            // Show loading state
            const uploadBtn = document.getElementById('uploadBtn');
            const originalText = uploadBtn.innerHTML;
            uploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...';
            uploadBtn.disabled = true;

            Swal.fire({
                title: 'Uploading File',
                html: 'Please wait while we process your file...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit the form
            this.submit();
        });

        // Show success/error messages with SweetAlert if they exist
        @if(session('success'))
            @php
                $summary = session('import_summary', []);
                $duplicates = session('import_duplicates', []);
                $errors = session('import_errors', []);
            @endphp
            
            let successHtml = `
                <div style="text-align: left; font-size: 0.9em;">
                    <div class="mb-3">
                        <h6 style="color: #28a745; margin-bottom: 10px;">📊 Import Summary:</h6>
                        <ul style="list-style: none; padding-left: 0;">
                            <li>✅ Total Rows Processed: <strong>{{ $summary['total_rows'] ?? 0 }}</strong></li>
                            <li>✅ Successfully Imported: <strong>{{ $summary['success_count'] ?? 0 }}</strong></li>
                            @if(isset($summary['duplicate_count']) && $summary['duplicate_count'] > 0)
                                <li style="color: #ffc107;">⚠️ Duplicates Found: <strong>{{ $summary['duplicate_count'] }}</strong></li>
                            @endif
                            @if(isset($summary['error_count']) && $summary['error_count'] > 0)
                                <li style="color: #dc3545;">❌ Errors: <strong>{{ $summary['error_count'] }}</strong></li>
                            @endif
                        </ul>
                    </div>
            `;
            
            @if(count($duplicates) > 0)
                successHtml += `
                    <div class="mb-3">
                        <h6 style="color: #ffc107; margin-bottom: 10px;">⚠️ Duplicate Records:</h6>
                        <div style="max-height: 150px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #f8f9fa;">
                            <table style="width: 100%; font-size: 0.8em;">
                                <thead>
                                    <tr style="background: #ffc107; color: #000;">
                                        <th style="padding: 5px;">Row</th>
                                        <th style="padding: 5px;">Matric</th>
                                        <th style="padding: 5px;">Course</th>
                                        <th style="padding: 5px;">Status</th>
                                        <th style="padding: 5px;">Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;
                @foreach($duplicates as $duplicate)
                    successHtml += `
                        <tr>
                            <td style="padding: 3px;">{{ $duplicate['row'] }}</td>
                            <td style="padding: 3px;">{{ $duplicate['matno'] }}</td>
                            <td style="padding: 3px;">{{ $duplicate['code'] }}</td>
                            <td style="padding: 3px;">{{ $duplicate['status'] }}</td>
                            <td style="padding: 3px;">{{ $duplicate['score'] }}</td>
                        </tr>
                    `;
                @endforeach
                successHtml += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            @endif
            
            @if(count($errors) > 0)
                successHtml += `
                    <div class="mb-3">
                        <h6 style="color: #dc3545; margin-bottom: 10px;">❌ Import Errors:</h6>
                        <div style="max-height: 150px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #f8f9fa;">
                            <table style="width: 100%; font-size: 0.8em;">
                                <thead>
                                    <tr style="background: #dc3545; color: #fff;">
                                        <th style="padding: 5px;">Row</th>
                                        <th style="padding: 5px;">Error Message</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;
                @foreach($errors as $error)
                    @php
                        $rowInfo = explode(':', $error);
                        $rowNumber = $rowInfo[0] ?? 'N/A';
                        $errorMessage = $error;
                    @endphp
                    successHtml += `
                        <tr>
                            <td style="padding: 3px;">{{ $rowNumber }}</td>
                            <td style="padding: 3px; word-break: break-word;">{{ $errorMessage }}</td>
                        </tr>
                    `;
                @endforeach
                successHtml += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            @endif
            
            successHtml += `</div>`;
            
            Swal.fire({
                icon: 'success',
                title: 'Upload Completed!',
                html: successHtml,
                width: '800px',
                confirmButtonColor: '#28a745',
                confirmButtonText: 'OK'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Upload Failed',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33'
            });
        @endif
    </script>

   <div class="card mt-4">
    <div class="card-header text-center bg-light">
        <strong>Upload Guidelines</strong>
    </div>
    <div class="card-body text-center">

        <p class="text-muted mb-2">Accepted formats:</p>
        <table class="table table-bordered w-auto mx-auto">
            <thead class="table-secondary">
                <tr>
                    <th>.xls</th>
                    <th>.xlsx</th>
                    <th>.csv</th>
                </tr>
            </thead>
        </table>

        <p class="text-muted mt-4 mb-2">Expected Headers:</p>
        <table class="table table-bordered w-auto mx-auto">
            <thead class="table-secondary">
                <tr>
                    <th>matno</th>
                    <th>code</th>
                    <th>status</th>
                    <th>score</th>
                    <th>wa</th>
                    <th>sec</th>
                    <th>dept</th>
                </tr>
                <tr>
                <td>20903</td>
                 <td>FRM 711</td>
                  <td>C</td>
                   <td>60</td>
                    <td>58.9</td>
                     <td>2003/2004</td>
                      <td>Computer Science</td>

                </tr>
            </thead>
        </table>

    </div>
</div>

</div>

</x-admin-layout>
