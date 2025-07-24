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


@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Upload ResultOld Records (Excel)</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('result_old.upload_excel') }}" method="POST" enctype="multipart/form-data" class="text-center">
        @csrf
        <div class="mb-3">
            <input type="file" name="file" class="form-control w-50 mx-auto">
            @error('file')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Upload Excel</button>
    </form>

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
