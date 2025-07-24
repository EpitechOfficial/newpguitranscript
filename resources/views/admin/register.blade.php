<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .cardEmpty {
            display: flex;
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), url('../img/no-invoice.png') center center no-repeat;
            justify-content: center;
            align-items: center;
            height: 50vh;
        }

        .card .empty {
            font-size: 2rem;
        }

        .program {
            display: flex;
            justify-content: space-between;
        }

        .table {
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
        }

        thead {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        th, td {
            vertical-align: middle !important;
            text-align: center;
        }

        .btn {
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .modal-content {
            border-radius: 1rem;
        }

        .modal-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        .modal-footer {
            background-color: #f8f9fa;
            border-bottom-left-radius: 1rem;
            border-bottom-right-radius: 1rem;
        }

        .alert {
            margin-top: 1rem;
            z-index: 9999;
        }
    </style>
</head>

<body>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Admin Users</h1>

    <!-- Button to Open the Modal -->
    <div class="text-end mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            Add New User
        </button>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <!-- Users Table -->
    <div class="table-responsive">
        <table class="table table-bordered" id="recordsTable">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                @php
                $status = [
                    1 => 'Record Officer',
                    2 => 'Transcript Officer',
                    3 => 'Key in Officer',
                    4 => 'Processing Officer',
                    5 => 'Filing Officer',
                    6 => 'Help Desk',
                ];
            @endphp
                <tr>
                    <td>{{ $user->fullname }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $status[$user->role] ?? 'Unknown Status' }}</td>
                    {{-- <td>{{ $user->role }}</td> --}}
                    <td>
                        <div class="form-check form-switch d-flex justify-content-center">
                            <input class="form-check-input toggle-status" type="checkbox"
                                data-id="{{ $user->id }}"
                                {{ $user->status == 'active' ? 'checked' : '' }}>
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-info btn-sm view-user" data-id="{{ $user->id }}">
                            View
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('adminusers.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="1">Record Officer</option>
                            <option value="2">Transcript Officer</option>
                            <option value="3">Key in Officer</option>
                            <option value="4">Processing Officer</option>
                            <option value="5">Filing Officer</option>
                            <option value="6">Help Desk</option>

                        </select>
                    </div>
                    <input type="hidden" name="status" value="active">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create User</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="viewUserContent">
            <!-- Fetched user data will appear here -->
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle status
    document.querySelectorAll('.toggle-status').forEach(item => {
        item.addEventListener('change', function() {
            let id = this.dataset.id;
            fetch(`/admin-users/toggle/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(response => response.json())
              .then(data => console.log(data));
        });
    });

    // View user
    document.querySelectorAll('.view-user').forEach(item => {
        item.addEventListener('click', function() {
            let id = this.dataset.id;
            fetch(`/admin-users/${id}`)
                .then(response => response.json())
                .then(data => {
                    let content = `
                        <div class="modal-header">
                            <h5 class="modal-title">User Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Full Name:</strong> ${data.fullname}</p>
                            <p><strong>Username:</strong> ${data.username}</p>
                            <p><strong>Role:</strong> ${data.role}</p>
                            <p><strong>Created At:</strong> ${data.created_at}</p>
                        </div>
                    `;
                    document.getElementById('viewUserContent').innerHTML = content;
                    var viewUserModal = new bootstrap.Modal(document.getElementById('viewUserModal'));
                    viewUserModal.show();
                });
        });
    });
});
</script>

</body>
</html>
