<x-admin-layout :pageName="'2025 Ph.D Convocation'">
    <div class="container">
        <div class="page-title">
            <div class="row w-full w-100">
                <div class="">
                    <h1 class="mb-0 pb-0 display-4" id="title">Ph.D Transcript Preparation</h1>
                </div>
            </div>
        </div>

        <div class="card mb-2">
            <div class="card-body">
                <h2 class="text-2xl font-bold mb-4">Candidate</h2>
                <div class="grid grid-cols-2 gap-4">
                    <p><strong>Name:</strong> {{ ($student->Othernames ?? 'N/A') . ' ' . ($student->Surname ?? '') }}
                    </p>
                    <p><strong>Gender:</strong> {{ $gender ?? ($student->sex ?? 'N/A') }}</p>
                    <p><strong>Matric Number:</strong> {{ $student->matric }}</p>
                    
                    <p><strong>Faculty:</strong>
                        {{ $student->faculty ?? ($results->first()->faculty->faculty ?? 'N/A') }}</p>
                    <p><strong>Department:</strong>
                        {{ $student->department ?? ($results->first()->department->department ?? 'N/A') }}</p>
                </div>


                <!-- Results table section -->
                @if (!empty($results))
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-dark">
                                    <th class="border p-2">Course Code</th>
                                    <th class="border p-2">Course Title</th>
                                    <th class="border p-2">Units</th>
                                    <th class="border p-2">Status</th>
                                    <th class="border p-2">Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $result)
                                    <tr class="border">
                                        <td class="border p-2">{{ $result['code'] ?? 'N/A' }}</td>
                                        <td class="border p-2">{{ $result['course_title'] ?? 'N/A' }}</td>
                                        <td class="border p-2">{{ $result['c_unit'] ?? 'N/A' }}</td>
                                        <td class="border p-2">{{ $result['status'] ?? 'N/A' }}</td>
                                        <td class="border p-2">{{ $result['score'] ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <hr>
                <form action="{{ route('admin.phd_convocation.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="matric" value="{{ $student->matric }}">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold block">Surname</label>
                            <input type="text" name="surname" value="{{ $student->Surname }}" class="form-control" required>
                        </div>
                        <div>
                            <label class="font-semibold block">Othernames</label>
                            <input type="text" name="othernames" value="{{ $student->Othernames }}"
                                class="form-control" required>
                        </div>
                        <div>
                            <label class="font-semibold block">Sex</label>
                            
                            <select name="sex" class="form-control" required>
    <option value="" >Select Gender</option>
    <option value="Male" {{ (isset($student->sex) && $student->sex == 'Male') ? 'selected' : '' }}>Male</option>
    <option value="Female" {{ (isset($student->sex) && $student->sex == 'Female') ? 'selected' : '' }}>Female</option>
</select>

                        </div>
                       
                        <div>
                            <label class="font-semibold block">Faculty</label>
                            <input type="text" name="faculty" value="{{ $student->faculty }}" class="form-control"
                                required>
                        </div>
                        <div>
                            <label class="font-semibold block">Department</label>
                            <input type="text" name="department" value="{{ $student->department }}"
                                class="form-control" required>
                        </div>

                        <div>
                            <label class="font-semibold block">Degree Awarded</label>
                            <input type="text" name="degree_awarded"
                                value="{{ $degreeAwarded ?? 'Doctor of Philosophy' }}" class="form-control" required>
                        </div>
                        <div>
                            <label class="font-semibold block">Date of Award</label>
                            <input type="text" name="award_date" value="{{ $dateAward ? \Carbon\Carbon::parse($dateAward)->format('j F, Y') : '' }}"  class="form-control"
                                required>
                        </div>

                        @if (!empty($results))
                            <div>
                                <label class="font-semibold block">CGPA</label>
                                <input type="text" name="cgpa" value="{{ $cgpa ?? 'N/A' }}" class="form-control"
                                    required>
                            </div>
                        @endif
                    </div>
                    <div class="mt-3">
                        <label class="font-semibold block">Thesis Title</label>
                        <textarea name="thesis_title" class="form-control" rows="3" required>{{ $thesisTitle ?? '' }}</textarea>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">Save & View Transcript</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
