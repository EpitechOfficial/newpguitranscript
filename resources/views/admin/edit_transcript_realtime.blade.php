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
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Edit Transcript (Realtime)</h2>
        <div class="mb-3 text-center">
            <input type="text" id="matricInput" class="form-control d-inline-block w-50" placeholder="Enter Matric Number">
            <button id="fetchBtn" class="btn btn-primary">Fetch Transcript</button>
            <span class="spinner" id="fetchSpinner">Loading...</span>
        </div>
        <div id="transcriptContainer"></div>
        <div class="text-center mt-3" id="saveBtnContainer" style="display:none;">
            <button id="saveChangesBtn" class="btn btn-success">Save Changes</button>
            <span class="spinner" id="saveSpinner">Saving...</span>
        </div>
    </div>
    <script>
        function renderTranscriptTable(data) {
            if (!data || !data.results || data.results.length === 0) {
                document.getElementById('saveBtnContainer').style.display = 'none';
                return '<div class="alert alert-warning text-center">No transcript found for this matric number.</div>';
            }
            document.getElementById('saveBtnContainer').style.display = 'block';
            let html = `<div class='mb-3'><strong>Name:</strong> ${data.name || 'N/A'} | <strong>Matric:</strong> ${data.matric || 'N/A'}</div>`;
            html += `<div class='table-responsive'><table class='table table-bordered' id='editTranscriptTable'>`;
            html += `<thead><tr><th>Course Code</th><th>Course Title</th><th>Units</th><th>Status</th><th>Score</th><th>Grade</th></tr></thead><tbody>`;
            data.results.forEach(function(row) {
                // If course exists in CourseOnline, make course_title and c_unit not editable
                const courseExists = row.course_exists ? true : false;
                html += `<tr data-id='${row.course_id || ''}' data-resultid='${row.id || ''}'>` +
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

        document.getElementById('fetchBtn').onclick = function() {
            const matric = document.getElementById('matricInput').value.trim();
            if (!matric) return alert('Enter a matric number.');
            document.getElementById('fetchSpinner').style.display = 'inline';
            fetch(`{{ route('admin.edit_transcript_realtime.fetch') }}?matric=${encodeURIComponent(matric)}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('fetchSpinner').style.display = 'none';
                    document.getElementById('transcriptContainer').innerHTML = renderTranscriptTable(data);
                })
                .catch(() => {
                    document.getElementById('fetchSpinner').style.display = 'none';
                    document.getElementById('transcriptContainer').innerHTML = '<div class="alert alert-danger">Error fetching transcript.</div>';
                });
        };

        document.getElementById('saveChangesBtn').onclick = function() {
            const table = document.getElementById('editTranscriptTable');
            if (!table) return;
            const rows = table.querySelectorAll('tbody tr');
            let payload = [];
            rows.forEach(tr => {
                let rowData = {
                    course_id: tr.getAttribute('data-id'),
                    result_id: tr.getAttribute('data-resultid')
                };
                tr.querySelectorAll('td').forEach(td => {
                    const field = td.getAttribute('data-field');
                    rowData[field] = td.innerText.trim();
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
                body: JSON.stringify({rows: payload})
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('saveSpinner').style.display = 'none';
                if (data.success) {
                    alert('Changes saved successfully!');
                } else {
                    alert('Some changes could not be saved.');
                }
            })
            .catch(() => {
                document.getElementById('saveSpinner').style.display = 'none';
                alert('Error saving changes.');
            });
        };
    </script>
</x-admin-layout> 
