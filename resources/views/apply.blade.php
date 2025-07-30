@php
    $notify = DB::table('notify')->where('matric', session('user')->matric)->first();
@endphp

<x-app-layout :pageName="'Apply'">
    <style>
        #surname,
        #othernames,
        #maiden_name {
            color: #000000 !important;
        } .mt-10{margin-top: 1rem}
        .dispatch-fields {
            display: block;
        }
        .ecopy-fields {
            display: none;
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
        <!-- Content Start -->
        <div class="card mb-2">
            <div class="card-body h-100">
                Welcome <h1>{{ session('user')->Surname }} {{ session('user')->Othernames }}</h1>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Content End -->
        <div class="card mb-2 w-100">
            <div class="card-body h-100 w-100">
                <h1 class="mb-0 pb-0 display-4" id="title">Transcript Request</h1>
                <nav class="breadcrumb-container w-100 d-inline-block" aria-label="breadcrumb">
                    <form id="transcriptForm" action="{{ route('dashboard.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <h2 class="mt-4">Please provide details of your Transcript request and add to cart</h2>
                        <div class="mt-10 col-md-12">
                            <div class="row w-full w-100">

                                <!-- Title -->
                                <div class="col form-group">
                                    <label for="title">Title</label>
                                    <select id="title" name="title" class="form-control" required>
                                        <option value="">Select Title</option>
                                        <option value="Mr." {{ $title == 'Mr' ? 'selected' : '' }}>Mr.</option>
                                        <option value="Mrs." {{ $title == 'Mrs' ? 'selected' : '' }}>Mrs.</option>
                                        <option value="Miss" {{ $title == 'Miss' ? 'selected' : '' }}>Miss</option>
                                    </select>

                                </div>

                                <!-- Gender -->
                                <div class="col form-group">
                                    <label for="sex">Gender</label>
                                    <select id="sex" name="sex" class="form-control" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ $sex == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $sex == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ $sex == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>

                                </div>
                                <div class="col form-group">
                                    <label for="surname">Surname </label>
                                    <input type="text" id="surname" name="surname" class="form-control"
                                        value="{{ $surname }}" required>
                                </div>
                                <div class="col form-group">
                                    <label for="othernames">Other Names</label>
                                    <input type="text" id="othernames" name="othernames" class="form-control"
                                        value="{{ $othernames }}" required>
                                </div>

                            </div>
                        </div>
                        <div class="mt-10 col-md-12">
                            <div class="row w-full w-100">
                                <div class="col form-group">
                                    <label for="maiden">Maiden Name</label>
                                    <input type="text" id="maiden" name="maiden" class="form-control"
                                        value="{{ $maidenname ?? '' }}">
                                </div>
                                <div class="col form-group">
                                    <label for="faculty">Faculty </label>
                                    <select id="faculty" class="form-control" name="faculty" required>
                                        <option value="{{ $faculty }}" selected>{{ $faculty }}</option>
                                        @foreach ($faculties as $fac)
                                            <option value="{{ $fac->id }}">{{ $fac->faculty }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col form-group">
                                    <label for="department">Department</label>
                                    <select id="department" class="form-control" name="department" required>
                                        <option value="{{ $department }}" selected>{{ $department }}</option>

                                    </select>
                                </div>
                                <div class="col form-group">
                                    <label for="degree">Degree</label>
                                    <select id="degree" class="form-control" name="degree" required>
                                        <option value="{{ $degree }}" selected>{{ $degree }}</option>

                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="mt-10 col-md-12">
                            <div class="row w-full w-100">
                                <div class="col form-group">
                                    <label for="field">Specialization</label>
                                    <select id="field" class="form-control" name="field" required>
                                        <option value="{{ $field }}" selected>{{ $field }}</option>

                                    </select>

                                </div>
                                <div class="col form-group">
                                    <label for="session_of_entry">Session of Entry</label>
                                    @if (!empty($secAdmin))
                                        <select id="session_of_entry" name="session_of_entry" class="form-control"
                                            required>
                                            <option value="">Select your Entry session</option>
                                            @foreach ($secAdmin as $session)
                                                <option value="{{ $session }}">{{ $session }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" id="session_of_entry" name="session_of_entry"
                                            class="form-control" required
                                            placeholder="Provide your Entry session (2018/2019)"
                                            pattern="^\d{4}/\d{4}$" title="Format must be YYYY/YYYY, e.g., 2018/2019">
                                    @endif
                                </div>
                                <div class="col form-group">
                                    <label for="session_of_graduation">Session of Graduation</label>
                                    @if (!empty($secGrad))
                                        <select id="session_of_graduation" name="session_of_graduation"
                                            class="form-control" required>
                                            <option value="">Select your Graduation session</option>
                                            @foreach ($secGrad as $session)
                                                <option value="{{ $session }}">{{ $session }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" id="session_of_graduation" name="session_of_graduation"
                                            class="form-control" required
                                            placeholder="Provide your Graduation Session (2018/2019)"
                                            pattern="^\d{4}/\d{4}$" title="Format must be YYYY/YYYY, e.g., 2018/2019">
                                    @endif
                                </div>

                            </div>
                        </div>
                        <div class="mt-10 col-md-12">

                            <div class="row w-full w-100">
                                <div class="col form-group">
                                    <label for="transcript_type">Transcript Type</label>
                                    <select id="transcript_type" class="form-control" name="transcript_type"
                                        required>
                                        <option value="">Select Transcript Type</option>
                                        @foreach ($requestTypes as $type)
                                            <option value="{{ $type->requesttype }}">{{ $type->requesttype }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col form-group">
                                    <label for="number_of_copies">Number of Copies</label>
                                    <select id="number_of_copies" name="number_of_copies" class="form-control"
                                        required>
                                        <option value="">Select Number of Copies</option>
                                        <option value="1">One (1)</option>
                                        <option value="2">Two (2)</option>
                                        <option value="3">Three (3)</option>
                                        <option value="4">Four (4)</option>
                                        <option value="5">Five (5)</option>
                                    </select>
                                </div>
                                
                                <!-- Dispatch Mode - Hidden for E-copy -->
                                <div class="col form-group dispatch-fields">
                                    <label for="dispatch_mode">Dispatch Mode</label>
                                    <select id="dispatch_mode" name="dispatch_mode" class="form-control">
                                        <option value="">Select Dispatch Mode</option>
                                        <option value="DHL">DHL</option>
                                        <option value="UPS">UPS</option>
                                        <option value="NIPOST">NIPOST</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        
                        <!-- Dispatch Fields - Hidden for E-copy -->
                        <div class="mt-10 col-md-12 dispatch-fields">
                            <div class="row w-full w-100">
                                <div class="col form-group">
                                    <label for="dispatch_country">Dispatch Country</label>
                                    <select id="dispatch_country" name="dispatch_country" class="form-control">
                                        <option value="">Select Country</option>
                                        @foreach (['Nigeria', 'United States', 'United Kingdom', 'Canada', 'Germany', 'France', 'India', 'China', 'South Africa', 'Ghana', 'Kenya', 'Australia', 'Other'] as $country)
                                            <option value="{{ $country }}">{{ $country }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col form-group">
                                    <label for="destination_address">Destination Address</label>
                                    <input type="text" id="destination_address" name="destination_address"
                                        class="form-control" placeholder="Enter destination address">
                                </div>
                                <div class="col form-group">
                                    <label for="destination2">Destination 2 (optional)</label>
                                    <input type="text" id="destination2" name="destination2" class="form-control"
                                        placeholder="Enter second address (optional)">
                                </div>
                            </div>
                        </div>

                        <!-- E-copy Fields - Shown only for E-copy -->
                        <div class="mt-10 col-md-12 ecopy-fields">
                            <div class="row w-full w-100">
                                <div class="col form-group">
                                    <label for="ecopy_email">E-copy Email</label>
                                    <input type="email" id="ecopy_email" name="ecopy_email" class="form-control"
                                        placeholder="Enter email for e-copy delivery">
                                </div>
                                <div class="col form-group">
                                    <label for="ecopy_address">E-copy Address</label>
                                    <input type="text" id="ecopy_address" name="ecopy_address" class="form-control"
                                        placeholder="Enter address for e-copy reference">
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 col-md-12">

                            <div class="row w-full w-100">
                                <div class="col form-group">
                                    <label for="file"><strong>Please Upload Notification of Result or
                                            Certificate</strong></label>
                                    <input type="file" id="file" name="file" required
                                        class="form-control" accept=".pdf,image/*">
                                    <small class="form-text text-muted">Accepted formats: PDF, JPG, PNG, etc.</small>
                                </div>
                                <div class="col form-group">
                                    <label for="wes_file"><strong>WES Upload (optional)</strong></label>
                                    <input type="file" id="wes_file" name="wes_file" class="form-control"
                                        accept=".pdf,image/*">
                                    <small class="form-text text-muted">Accepted formats: PDF, JPG, PNG, etc.</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-0 col d-flex justify-content-center">
                            <button class="btn btn-primary mt-4 w-full w-50" type="submit" id="addToCart">Add to
                                Cart</button>
                        </div>


                    </form>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

     <script type="text/javascript">
        $(document).ready(function() {
            // Function to toggle fields based on transcript type
            function toggleFieldsBasedOnTranscriptType(transcriptType) {
                var isEcopy = transcriptType && transcriptType.toLowerCase().includes('e-copy');
                
                if (isEcopy) {
                    // Hide dispatch fields and show e-copy fields
                    $('.dispatch-fields').hide();
                    $('.ecopy-fields').show();
                    
                    // Remove required attribute from dispatch fields
                    $('#dispatch_mode, #dispatch_country, #destination_address').removeAttr('required');
                    
                    // Add required attribute to e-copy fields
                    $('#ecopy_email').attr('required', true);
                } else {
                    // Show dispatch fields and hide e-copy fields
                    $('.dispatch-fields').show();
                    $('.ecopy-fields').hide();
                    
                    // Add required attribute to dispatch fields
                    $('#dispatch_mode, #dispatch_country, #destination_address').attr('required', true);
                    
                    // Remove required attribute from e-copy fields
                    $('#ecopy_email').removeAttr('required');
                }
            }

            // Handle transcript type change
            $('#transcript_type').on('change', function() {
                var transcriptType = this.value;
                toggleFieldsBasedOnTranscriptType(transcriptType);
                setDispatchCountryOptions(transcriptType);
            });

            // Initialize on page load
            toggleFieldsBasedOnTranscriptType($('#transcript_type').val());

            $('#faculty').on('change', function() {
                var facultyId = this.value;
                $('#department').html('<option value="">Select Department</option>');
                $('#degree').html('<option value="">Select Degree</option>');
                $('#field').html('<option value="">Select Specialization</option>');
                if (facultyId) {
                    $.ajax({
                        url: '/get-departments/' + facultyId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $('#department').append('<option value="' + value.id +
                                    '">' + value.department + '</option>');
                            });
                        }
                    });
                }
            });

            $('#department').on('change', function() {
                var departmentId = this.value;
                $('#degree').html('<option value="">Select Degree</option>');
                $('#field').html('<option value="">Select Specialization</option>');
                if (departmentId) {
                    $.ajax({
                        url: '/get-degrees/' + departmentId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $('#degree').append('<option value="' + value.id +
                                    '">' + value.degree + '</option>');
                            });
                        }
                    });
                }
            });

            $('#degree').on('change', function() {
                var degreeId = this.value;
                var departmentId = $('#department').val();
                $('#field').html('<option value="">Select Specialization</option>');
                if (degreeId && departmentId) {
                    $.ajax({
                        url: '/get-specializations/' + degreeId + '/' + departmentId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $('#field').append('<option value="' + value.id + '">' +
                                    value.field_title + '</option>');
                            });
                        }
                    });
                }
            });

            var allCountries = [
                'Nigeria', 'United States', 'United Kingdom', 'Canada', 'Germany', 'France', 'India', 'China', 'South Africa', 'Ghana', 'Kenya', 'Australia', 'Other'
            ];
            var $dispatchCountry = $('#dispatch_country');
            
            function setDispatchCountryOptions(type) {
                if (type === 'Transcript Within Nigeria') {
                    $dispatchCountry.html('<option value="Nigeria">Nigeria</option>');
                } else {
                    $dispatchCountry.html('<option value="">Select Country</option>');
                    allCountries.forEach(function(country) {
                        $dispatchCountry.append('<option value="' + country + '">' + country + '</option>');
                    });
                }
            }

            setDispatchCountryOptions($('#transcript_type').val());
        });
    </script>

</x-app-layout>