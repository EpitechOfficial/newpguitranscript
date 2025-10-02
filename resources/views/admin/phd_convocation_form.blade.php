<x-admin-layout :pageName="'2025 Ph.D Convocation'">
    <div class="container">
        <div class="page-title">
            <div class="row w-full w-100">
                <div class="">
                    <h1 class="mb-0 pb-0 display-4 text-center" id="title">2025 Ph.D Convocation</h1>
                </div>
            </div>
        </div>

        <div class="card mb-2">
            <div class="card-body h-100">
                <form method="POST" action="{{ route('admin.phd_convocation.find') }}">
                    @csrf
                    <div class="mb-3 text-center">
                        <input type="text" name="matric" id="matricInput" class="form-control d-inline-block w-25" placeholder="Enter Matric Number" required>
                        <select name="session" id="secDropdown" class="form-control d-inline-block w-20 ms-2" style="display:none;" required>
                            <option value="NoResult">No Course Work</option>
                        </select>
                        <button type="submit" id="fetchBtn" class="btn btn-primary ms-2" disabled>Proceed</button>
                        <span class="spinner" id="fetchSpinner" style="display:none;">Loading...</span>
                    </div>
                </form>
                @if(session('error'))
                    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .spinner {
            display: none;
        }
        .w-25 {
            width: 25% !important;
        }
        .w-20 {
            width: 20% !important;
        }
        .ms-2 {
            margin-left: 0.5rem !important;
        }
        .d-inline-block {
            display: inline-block !important;
        }
        .form-control {
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            height: calc(1.5em + 0.75rem + 2px);
            vertical-align: middle;
        }
        .text-center {
            text-align: center !important;
        }
    </style>

    <script>
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
                fetch(`{{ route('admin.phd_convocation.fetch_sessions') }}?matric=${encodeURIComponent(matric)}`)
                    .then(res => res.json())
                    .then(data => {
                        secDropdown.innerHTML = '<option value="NoResult">No Course Work</option>';
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
    </script>
</x-admin-layout>