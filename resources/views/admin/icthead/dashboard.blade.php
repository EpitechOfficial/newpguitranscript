<x-admin-layout :pageName="'ICT Head Dashboard'">
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
        }

        #recordsTable th:last-child,
        #recordsTable td:last-child {
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

        .d-flex button {
            width: 100px;
        }

        /* Hover effect for buttons */
        .btn-primary:hover {
            background-color: #0a2b4f;
            border-color: #0a2b4f;
        }

        .btn-warning:hover {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-success:hover {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
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

        .stats-card {
            color: white !important;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
        }

        .stats-card:nth-child(1) {
            background: #0a2b4f;
            /* Total Users - Blue */
        }

        .stats-card-success {
            background: #28a745 !important;
            /* Active Users - Green */
        }

        .stats-card-warning {
            background: #ffc107 !important;
            /* Inactive Users - Yellow */
            color: #000 !important;
            /* Black text for readability on yellow */
        }

        .stats-card:nth-child(4) {
            background: #dc3545 !important;
            /* ICT Head Users - Red */
        }

        .stats-card h3 {
            margin: 0;
            font-size: 2rem;
            font-weight: bold;
            color: white !important;
        }

        .stats-card p {
            margin: 0;
            opacity: 0.9;
        }

        .modal-content {
            border-radius: 1rem;
        }

        .modal-header {
            background-color: #0a2b4f;
            color: white !important;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        .modal-title {
            color: white !important;
        }

        .modal-footer {
            background-color: #f8f9fa;
            border-bottom-left-radius: 1rem;
            border-bottom-right-radius: 1rem;
        }

        /* Custom toggle switch styling */
        .form-check-input:checked {
            background-color: #198754;
        }

        .form-check-input:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .form-check-input {
            width: 3rem;
            height: 1rem;
            margin-top: 0;
        }

        .status-toggle-container {
            min-width: 120px;
        }
    </style>

    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title">
            <div class="row w-full w-100">
                <div class="">
                    <h1 class="mb-0 pb-0 display-4" id="title">ICT Head Dashboard</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    </nav>
                </div>
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

        <!-- Welcome Card Start -->
        <div class="card mb-2">
            <div class="card-body h-100">
                Welcome <h1>{{ session('admin_user')->fullname }}</h1>
            </div>
        </div>
        <!-- Welcome Card End -->

        <!-- Statistics Cards Start -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card stats-card-primary">
                    <h3 id="totalUsers">-</h3>
                    <p>Total Users</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card stats-card-success">
                    <h3 id="activeUsers">-</h3>
                    <p>Active Users</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card stats-card-warning">
                    <h3 id="inactiveUsers">-</h3>
                    <p>Inactive Users</p>
                </div>
            </div>

        </div>
        <!-- Statistics Cards End -->

        <!-- Admin Users Management Start -->
        <div class="card mb-2 w-100">
            <div class="card-body h-100 w-100">
                <div class="program">
                    <h1 class="mb-0 pb-0 display-4" id="title">Admin Users Management</h1>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addUserModal">
                        Add New User
                    </button>
                </div>
                <br>
                <nav class="breadcrumb-container w-100 d-inline-block" aria-label="breadcrumb">
                    <div class="table-responsive">
                        <table id="recordsTable" class="table table-bordered table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>S/N</th>
                                    <th>Full Name</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <!-- Users will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Admin Users Management End -->
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New Admin User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addUserForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="2">Transcript Officer</option>
                                <option value="3">Key in Officer</option>
                                {{-- <option value="4">Processing Officer</option> --}}
                                <option value="5">Filing Officer</option>
                                <option value="6">Help Desk</option>
                                <option value="7">Record Officer</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit Admin User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editUserForm">
                    <input type="hidden" id="editUserId" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editFullname" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="editFullname" name="fullname" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Password <small class="text-muted">(Leave
                                    blank to keep current password)</small></label>
                            <input type="password" class="form-control" id="editPassword" name="password"
                                minlength="6">
                        </div>
                        <div class="mb-3">
                            <label for="editRole" class="form-label">Role</label>
                            <select class="form-select" id="editRole" name="role" required>
                                <option value="1">ICT Head</option>
                                <option value="2">Transcript Officer</option>
                                <option value="3">Key in Officer</option>
                                <option value="4">Processing Officer</option>
                                <option value="5">Filing Officer</option>
                                <option value="6">Help Desk</option>
                                <option value="7">Record Officer</option>
                                {{-- <option value="8">Super Admin</option> --}}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-select" id="editStatus" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewUserModalLabel">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Full Name:</strong> <span id="viewFullname"></span></p>
                            <p><strong>Username:</strong> <span id="viewUsername"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Role:</strong> <span id="viewRole"></span></p>
                            <p><strong>Status:</strong> <span id="viewStatus"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p><strong>Created At:</strong> <span id="viewCreatedAt"></span></p>
                            <p><strong>Last Updated:</strong> <span id="viewUpdatedAt"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Load initial data
            loadUsers();
            loadStats();

            // Show success alert if exists
            if (document.getElementById('success-alert')) {
                const alert = document.getElementById('success-alert');
                setTimeout(() => {
                    alert.style.opacity = '1';
                    alert.style.transform = 'translateX(0)';
                }, 100);
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(100%)';
                }, 5000);
            }

            // Add User Form Submit
            $('#addUserForm').on('submit', function(e) {
                e.preventDefault();
                addUser();
            });

            // Edit User Form Submit
            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();
                updateUser();
            });
        });

        function loadUsers() {
            console.log('Loading users...');
            $.ajax({
                url: '{{ route('admin.icthead.users.index') }}',
                type: 'GET',
                success: function(response) {
                    console.log('Users response:', response);
                    let html = '';
                    if (response.success && response.users && response.users.length > 0) {
                        response.users.forEach((user, index) => {
                            html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${user.fullname}</td>
                                    <td>${user.username}</td>
                                    <td>${getRoleName(user.role)}</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center status-toggle-container">
                                            <div class="form-check form-switch me-2">
                                                <input class="form-check-input" type="checkbox" role="switch" 
                                                       id="toggle_${user.id}" 
                                                       ${user.status === 'active' ? 'checked' : ''} 
                                                       onchange="toggleStatus(${user.id}, this.checked)">
                                            </div>
                                            <span class="badge ${user.status === 'active' ? 'bg-success' : 'bg-danger'}">
                                                ${user.status}
                                            </span>
                                        </div>
                                    </td>
                                    <td>${new Date(user.created_at).toLocaleDateString()}</td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button class="btn btn-primary btn-sm" onclick="viewUser(${user.id})">
                                                View
                                            </button>
                                            <button class="btn btn-warning btn-sm" onclick="editUser(${user.id})">
                                                Edit
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        html = '<tr><td colspan="7" class="text-center">No users found</td></tr>';
                    }
                    $('#usersTableBody').html(html);
                },
                error: function(xhr) {
                    console.error('Error loading users:', xhr);
                    $('#usersTableBody').html(
                        '<tr><td colspan="7" class="text-center text-danger">Error loading users</td></tr>');
                }
            });
        }

        function loadStats() {
            console.log('Loading stats...');
            $.ajax({
                url: '{{ route('admin.icthead.stats') }}',
                type: 'GET',
                success: function(response) {
                    console.log('Stats response:', response);
                    if (response.success && response.stats) {
                        $('#totalUsers').text(response.stats.total_users);
                        $('#activeUsers').text(response.stats.active_users);
                        $('#inactiveUsers').text(response.stats.inactive_users);
                        $('#ictHeadUsers').text(response.stats.users_by_role['1'] || 0);
                    }
                },
                error: function(xhr) {
                    console.error('Error loading stats:', xhr);
                }
            });
        }

        function getRoleName(role) {
            const roles = {
                '1': 'ICT Head',
                '2': 'Transcript Officer',
                '3': 'Key in Officer',
                '4': 'Processing Officer',
                '5': 'Filing Officer',
                '6': 'Help Desk',
                '7': 'Record Officer'
            };
            return roles[role] || role;
        }

        function addUser() {
            const formData = {
                fullname: $('#fullname').val(),
                username: $('#username').val(),
                password: $('#password').val(),
                role: $('#role').val(),
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: '{{ route('admin.icthead.users.store') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                   
                    showAlert('User added successfully!', 'success');
                    window.location.reload();
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        Object.keys(errors).forEach(key => {
                            errorMessage += errors[key][0] + '\n';
                        });
                        showAlert(errorMessage, 'error');
                    } else {
                        showAlert('Error adding user. Please try again.', 'error');
                    }
                }
            });
        }

        function viewUser(id) {
            $.ajax({
                url: `{{ route('admin.icthead.users.index') }}/${id}`,
                type: 'GET',
                success: function(response) {
                    $('#viewFullname').text(response.user.fullname);
                    $('#viewUsername').text(response.user.username);
                    $('#viewRole').text(getRoleName(response.user.role));
                    $('#viewStatus').text(response.user.status);
                    $('#viewCreatedAt').text(new Date(response.user.created_at).toLocaleString());
                    $('#viewUpdatedAt').text(new Date(response.user.updated_at).toLocaleString());
                    $('#viewUserModal').modal('show');
                },
                error: function(xhr) {
                    console.error('Error loading user:', xhr);
                }
            });
        }

        function editUser(id) {
            $.ajax({
                url: `{{ route('admin.icthead.users.index') }}/${id}`,
                type: 'GET',
                success: function(response) {
                    $('#editUserId').val(response.user.id);
                    $('#editFullname').val(response.user.fullname);
                    $('#editUsername').val(response.user.username);
                    $('#editPassword').val(''); // Clear password field
                    $('#editRole').val(response.user.role);
                    $('#editStatus').val(response.user.status);
                    $('#editUserModal').modal('show');
                },
                error: function(xhr) {
                    console.error('Error loading user:', xhr);
                }
            });
        }

        function updateUser() {
            const id = $('#editUserId').val();
            const formData = {
                fullname: $('#editFullname').val(),
                username: $('#editUsername').val(),
                password: $('#editPassword').val(),
                role: $('#editRole').val(),
                status: $('#editStatus').val(),
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: `{{ route('admin.icthead.users.index') }}/${id}`,
                type: 'PUT',
                data: formData,
                success: function(response) {
                    $('#editUserModal').modal('hide');
                    loadUsers();
                    loadStats();
                    showAlert('User updated successfully!', 'success');
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        Object.keys(errors).forEach(key => {
                            errorMessage += errors[key][0] + '\n';
                        });
                        showAlert(errorMessage, 'error');
                    } else {
                        showAlert('Error updating user. Please try again.', 'error');
                    }
                }
            });
        }

        function toggleStatus(id, isActive) {
            $.ajax({
                url: `{{ route('admin.icthead.users.toggle', ['id' => ':id']) }}`.replace(':id', id),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Update the badge text and color immediately for better UX
                    const toggleElement = $(`#toggle_${id}`);
                    const badge = toggleElement.closest('td').find('.badge');

                    if (isActive) {
                        badge.removeClass('bg-danger').addClass('bg-success').text('active');
                    } else {
                        badge.removeClass('bg-success').addClass('bg-danger').text('inactive');
                    }

                    loadStats();
                    showAlert('User status updated successfully!', 'success');
                },
                error: function(xhr) {
                    // Revert the toggle if there was an error
                    const toggleElement = $(`#toggle_${id}`);
                    toggleElement.prop('checked', !isActive);

                    showAlert('Error updating user status. Please try again.', 'error');
                }
            });
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                $.ajax({
                    url: `{{ route('admin.icthead.users.index') }}/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        loadUsers();
                        loadStats();
                        showAlert('User deleted successfully!', 'success');
                    },
                    error: function(xhr) {
                        showAlert('Error deleting user. Please try again.', 'error');
                    }
                });
            }
        }

        function showAlert(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow-lg" 
                     role="alert" style="z-index: 1050;">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('body').append(alertHtml);

            setTimeout(() => {
                $('.alert').fadeOut();
            }, 5000);
        }

        // Initialize dashboard when page loads
        $(document).ready(function() {
            loadUsers();
            loadStats();
        });
    </script>
</x-admin-layout>
