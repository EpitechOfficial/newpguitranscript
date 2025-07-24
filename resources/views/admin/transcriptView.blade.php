<div class="p-6 shadow-md mb-6">
                <div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- <p><strong>Name:</strong>
                            {{ $biodata->othername && $biodata->surname ? $biodata->othername . ' ' . $biodata->surname : $biodata->name }}
                        </p>
                        <p><strong>Gender:</strong> {{ $gender ??$biodata->sex}}</p>
                        <p><strong>Matric Number:</strong> {{ $biodata->matric }}</p>
                        <p><strong>Session Admitted:</strong>
                            {{ $biodata->yr_of_entry ?? $results->first()->yr_of_entry }}</p>
                        <p><strong>Faculty:</strong>
                            {{ $biodata->faculty ?? ($results->first()->faculty->faculty ?? 'N/A') }}</p>
                        <p><strong>Department:</strong>
                            {{ $biodata->department ?? ($results->first()->department->department ?? 'N/A') }}</p> --}}

                        @if ($biodata)
                            <p><strong>Name:</strong>
                                {{ $biodata->Othernames && $biodata->Surname ? $biodata->Othernames . ' ' . $biodata->Surname : $biodata->name ?? 'N/A' }}
                            </p>
                            <p><strong>Gender:</strong> {{ $gender ?? ($biodata->sex ?? 'N/A') }}</p>
                            <p><strong>Matric Number:</strong> {{ $biodata->matric ?? 'N/A' }}</p>
                            <p><strong>Session Admitted:</strong>
                                {{ $biodata->sessionadmin ?? ($results->first()->sec ?? 'N/A') }}
                            </p>
                            <p><strong>Faculty:</strong>
                                {{ $biodata->faculty ?? ($results->first()->faculty->faculty ?? 'N/A') }}
                            </p>
                            <p><strong>Department:</strong>
                                {{ $biodata->department ?? ($results->first()->department->department ?? 'N/A') }}
                            </p>
                        @else
                            <p><strong>Name:</strong> N/A</p>
                            <p><strong>Gender:</strong> {{ $gender ?? 'N/A' }}</p>
                            <p><strong>Matric Number:</strong> N/A</p>
                            <p><strong>Session Admitted:</strong> {{ $results->first()->yr_of_entry ?? 'N/A' }}</p>
                            <p><strong>Faculty:</strong> {{ $results->first()->faculty->faculty ?? 'N/A' }}</p>
                            <p><strong>Department:</strong> {{ $results->first()->department->department ?? 'N/A' }}</p>
                        @endif


                    </div>

                </div>

                <hr>

                <!-- Academic Results -->
                <div>
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
                                        <td class="border p-2">{{ $result->course->course ?? 'N/A' }}</td>
                                        <td class="border p-2">{{ $result->course->title ?? 'N/A' }}</td>
                                        <td class="border p-2">{{ $result->course->unit ?? ($result->cunit ?? 'N/A') }}</td>

                                        <td class="border p-2">{{ $result->status ?? ($result->cstatus ?? 'N/A') }}
                                        </td>
                                        <td class="border p-2">{{ $result->score }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <p class="text-center bold"><strong>Cumulative Grade Point Average (CGPA) Score for the Degree
                                of Master is {{ $biodata->award ?? '' }}</strong> </p>
                        <div class="test">
                            <div>
                                <p>
                                    Degree Awarded: {{ $biodata->programme ?? '' }}
                                </p>
                            </div>
                            <div>
                                <p>
                                    Date of Awarded: {{ $biodata->programme ?? '' }}
                                </p>
                            </div>


                        </div>
                        <p><strong>Area of Specialization:</strong>
                            {{ $biodata->feildofinterest ?? ($results->first()->specialization->field_title ?? 'N/A') }}
                        </p>

                    </div>
                </div>



            </div>
