<x-admin-layout :pageName="'Edit Transcript (Realtime)'">
    <style>
        .editable {
            background: #f9f9f9;
            border: 1px solid #ddd;
            min-width: 80px;
            padding: 4px;
        }
        .editable:focus {
            outline: 2px solid #007bff;
            background: #e6f0ff;
        }
        .spinner {
            display: none;
        }
    </style>
    <!-- SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Edit Transcript (Realtime)</h2>
        <div class="mb-3 text-center">
            <input type="text" id="matricInput" class="form-control d-inline-block w-25" placeholder="Enter Matric Number">
            <select id="secDropdown" class="form-control d-inline-block w-20 ms-2" style="display:none;">
                <option value="">Select Session</option>
            </select>
            <button id="fetchBtn" class="btn btn-primary ms-2" disabled>Fetch Transcript</button>
            <span class="spinner" id="fetchSpinner">Loading...</span>
        </div>
        <div id="transcriptContainer"></div>
        <div class="text-center mt-3" id="saveBtnContainer" style="display:none;">
            <button id="saveChangesBtn" class="btn btn-success">Save Changes</button>
            <button id="deleteSelectedBtn" class="btn btn-danger ms-2">Delete Selected</button>
            <span class="spinner" id="saveSpinner">Saving...</span>
            <span class="spinner" id="deleteSpinner">Deleting...</span>
        </div>
    </div>
    <script>
        function renderTranscriptTable(data) {
            if (!data || !data.results || data.results.length === 0) {
                document.getElementById('saveBtnContainer').style.display = 'none';
                return '<div class="alert alert-warning text-center">No transcript found for this matric number.</div>';
            }
            document.getElementById('saveBtnContainer').style.display = 'block';
            let html = `<div class='mb-3'><strong>Name:</strong> ${data.name || 'N/A'} | <strong>Matric:</strong> ${data.matric || 'N/A'} | <strong>Session:</strong> ${data.sec || 'N/A'}</div>`;
            html += `<div class='table-responsive'><table class='table table-bordered' id='editTranscriptTable'>`;
            html += `<thead><tr><th><input type="checkbox" id="selectAll" class="form-check-input"></th><th>Course Code</th><th>Course Title</th><th>Units</th><th>Status</th><th>Score</th><th>Grade</th></tr></thead><tbody>`;
            data.results.forEach(function(row) {
                // If course exists in CourseOnline, make course_title and c_unit not editable
                const courseExists = row.course_exists ? true : false;
                html += `<tr data-id='${row.course_id || ''}' data-resultid='${row.id || ''}'>` +
                    `<td><input type="checkbox" class="form-check-input row-checkbox" data-resultid='${row.id || ''}'></td>` +
                    `<td contenteditable='true' class='editable' data-field='code'>${row.code || ''}</td>` +
                    `<td contenteditable='${!courseExists}' class='editable' data-field='course_title'>${row.course_title || ''}</td>` +
                    `<td contenteditable='${!courseExists}' class='editable' data-field='c_unit'>${row.c_unit || ''}</td>` +
                    `<td contenteditable='true' class='editable' data-field='status'>${row.status || ''}</td>` +
                    `<td contenteditable='true' class='editable' data-field='score'>${row.score || ''}</td>` +
                `</tr>`;
            });
            html += `</tbody></table></div>`;
            return html;
        }

        // Function to check if both fields are filled and enable/disable fetch button
        function checkFormValidity() {
            const matric = document.getElementById('matricInput').value.trim();
            const sec = document.getElementById('secDropdown').value;
            const fetchBtn = document.getElementById('fetchBtn');
            
            if (matric && sec) {
                fetchBtn.disabled = false;
                fetchBtn.classList.remove('btn-secondary');
                fetchBtn.classList.add('btn-primary');
            } else {
                fetchBtn.disabled = true;
                fetchBtn.classList.remove('btn-primary');
                fetchBtn.classList.add('btn-secondary');
            }
        }

        // Handle matric input change to populate sec dropdown
        document.getElementById('matricInput').addEventListener('input', function() {
            const matric = this.value.trim();
            const secDropdown = document.getElementById('secDropdown');
            
            if (matric.length >= 3) {
                // Fetch available sessions for this matric number
                fetch(`{{ route('admin.edit_transcript_realtime.fetch_sessions') }}?matric=${encodeURIComponent(matric)}`)
                    .then(res => res.json())
                    .then(data => {
                        secDropdown.innerHTML = '<option value="">Select Session</option>';
                        if (data.sessions && data.sessions.length > 0) {
                            data.sessions.forEach(session => {
                                secDropdown.innerHTML += `<option value="${session}">${session}</option>`;
                            });
                            secDropdown.style.display = 'inline-block';
                        } else {
                            secDropdown.style.display = 'none';
                        }
                        checkFormValidity();
                    })
                    .catch(() => {
                        secDropdown.style.display = 'none';
                        checkFormValidity();
                    });
            } else {
                secDropdown.style.display = 'none';
                secDropdown.innerHTML = '<option value="">Select Session</option>';
                checkFormValidity();
            }
        });

        // Handle sec dropdown change
        document.getElementById('secDropdown').addEventListener('change', function() {
            checkFormValidity();
        });

        document.getElementById('fetchBtn').onclick = function() {
            const matric = document.getElementById('matricInput').value.trim();
            const sec = document.getElementById('secDropdown').value;
            
            document.getElementById('fetchSpinner').style.display = 'inline';
            fetch(`{{ route('admin.edit_transcript_realtime.fetch') }}?matric=${encodeURIComponent(matric)}&sec=${encodeURIComponent(sec)}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('fetchSpinner').style.display = 'none';
                    document.getElementById('transcriptContainer').innerHTML = renderTranscriptTable(data);
                })
                .catch(() => {
                    document.getElementById('fetchSpinner').style.display = 'none';
                    document.getElementById('transcriptContainer').innerHTML = '<div class="alert alert-danger">Error fetching transcript.</div>';
                    Swal.fire({
                        icon: 'error',
                        title: 'Fetch Error',
                        text: 'Error fetching transcript data.',
                        confirmButtonColor: '#d33'
                    });
                });
        };

        // Handle select all checkbox
        document.addEventListener('change', function(e) {
            if (e.target.id === 'selectAll') {
                const checkboxes = document.querySelectorAll('.row-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = e.target.checked;
                });
            }
        });

        // Handle individual row checkboxes
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('row-checkbox')) {
                const selectAllCheckbox = document.getElementById('selectAll');
                const checkboxes = document.querySelectorAll('.row-checkbox');
                const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                
                if (checkedBoxes.length === checkboxes.length) {
                    selectAllCheckbox.checked = true;
                } else {
                    selectAllCheckbox.checked = false;
                }
            }
        });

        document.getElementById('saveChangesBtn').onclick = function() {
            const table = document.getElementById('editTranscriptTable');
            if (!table) return;
            const matric = document.getElementById('matricInput').value.trim();
            const sec = document.getElementById('secDropdown').value;
            
            const rows = table.querySelectorAll('tbody tr');
            let payload = [];
            rows.forEach(tr => {
                let rowData = {
                    course_id: tr.getAttribute('data-id'),
                    result_id: tr.getAttribute('data-resultid')
                };
                tr.querySelectorAll('td').forEach(td => {
                    const field = td.getAttribute('data-field');
                    if (field) {
                        rowData[field] = td.innerText.trim();
                    }
                });
                payload.push(rowData);
            });
            document.getElementById('saveSpinner').style.display = 'inline';
            fetch(`{{ route('admin.edit_transcript_realtime.save') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    rows: payload,
                    matric: matric,
                    sec: sec
                })
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('saveSpinner').style.display = 'none';
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved!',
                        text: 'Changes saved successfully!',
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        // Refresh the transcript data
                        document.getElementById('fetchBtn').click();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Save Failed',
                        text: 'Some changes could not be saved.',
                        confirmButtonColor: '#d33'
                    });
                }
            })
            .catch(() => {
                document.getElementById('saveSpinner').style.display = 'none';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error saving changes.',
                    confirmButtonColor: '#d33'
                });
            });
        };

        document.getElementById('deleteSelectedBtn').onclick = function() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            if (checkedBoxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one record to delete.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            // Get selected course details
            const selectedCourses = [];
            checkedBoxes.forEach(checkbox => {
                const row = checkbox.closest('tr');
                const courseCode = row.querySelector('[data-field="code"]').innerText.trim();
                const courseTitle = row.querySelector('[data-field="course_title"]').innerText.trim();
                selectedCourses.push(`${courseCode} - ${courseTitle}`);
            });

            const courseList = selectedCourses.map(course => `<li>${course}</li>`).join('');

            Swal.fire({
                icon: 'warning',
                title: 'Confirm Deletion',
                html: `
                    <p>Are you sure you want to delete <strong>${checkedBoxes.length}</strong> selected record(s)?</p>
                    <div class="text-left">
                        <strong>Selected Courses:</strong>
                        <ul class="text-left mt-2" style="max-height: 200px; overflow-y: auto;">
                            ${courseList}
                        </ul>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete them!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const matric = document.getElementById('matricInput').value.trim();
                    const sec = document.getElementById('secDropdown').value;
                    const resultIds = Array.from(checkedBoxes).map(checkbox => checkbox.getAttribute('data-resultid'));

                    document.getElementById('deleteSpinner').style.display = 'inline';
                    fetch(`{{ route('admin.edit_transcript_realtime.delete') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            result_ids: resultIds,
                            matric: matric,
                            sec: sec
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('deleteSpinner').style.display = 'none';
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: `Successfully deleted ${data.deleted_count} record(s).`,
                                confirmButtonColor: '#28a745'
                            }).then(() => {
                                // Refresh the transcript data
                                document.getElementById('fetchBtn').click();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Deletion Failed',
                                text: 'Some records could not be deleted.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    })
                    .catch(() => {
                        document.getElementById('deleteSpinner').style.display = 'none';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error deleting records.',
                            confirmButtonColor: '#d33'
                        });
                    });
                }
            });
        };
    </script>
</x-admin-layout> 
