<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Laralink">
    <!-- Site Title -->
    <title>Transcript Application | Student Transcript</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        body,
        html {
            background: #fff !important;
            font-family: serif !important;
            font-size: 1rem !important;
        }

        th {
            text-align: center !important;
        }

        td {
            border: none !important;
            padding-bottom: 0 !important;

        }

        .test {
            display: flex !important;
            justify-content: space-between !important;
        }

        .sign {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .sign img {
            width: 120px;
        }

        .sign p,
        hr {
            border-top: 2px solid black;
        }

        .mt {
            margin-top: 1rem;
        }

        .text-center {
            text-align: center;
        }

        .bb {
            border-bottom: 2px solid black;
        }

        .afterHead2 {
            padding-top: 12rem !important;
        }

        .info-container {
            display: grid;
            grid-template-columns: 200px auto;
            gap: 10px 20px;
            max-width: 600px;
            align-items: center;
            margin-bottom: 2rem !important;
        }

        .info-container strong {
            text-align: left;
            font-weight: bold;
        }

        .info-container span {
            text-align: left;
            display: block;
        }

        .header {
            color: #0a2b4f !important;
            margin-bottom: 2rem !important;
        }

        .header .title {
            text-align: center;
        }

        .header .title h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 900;
            color: #0a2b4f !important;

        }

        .header .title h2 {
            margin: 0;
            font-size: 1.7rem;
            font-weight: 600;
            color: #0a2b4f !important;

        }

        .header .title p {
            margin: 0;
            font-size: 1rem;
            font-style: italic;
            font-weight: 600;
        }

        .address {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 0 !important;
            margin: 0 !important;
        }

        .address .left,
        .address .right {
            flex: 1;
        }

        .address .center {
            flex: 0 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .address .center p {
            margin: 0;
            font-size: 0.75rem;
            font-style: italic;
            text-align: center;

        }

        .address .center img {
            width: 6rem;
        }

        .watermark {
            position: fixed;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 5rem;
            color: #0a2b4f;
            opacity: 0.4;
            white-space: nowrap;
            pointer-events: none;
            z-index: 9999;
            width: 100vw;
            text-align: center;
            font-weight: bold;
            user-select: none;
        }

        @media print {
            .page-break {
                page-break-before: always;
            }
        }

        .underline {
            text-decoration: underline;
        }

        .bold {
            font-weight: 700 !important;
        }

        .address p {
            padding: 0 !important;
            margin: 0 !important;
        }

        .add-width {
            width: 33.33%;
        }

        .italic {
            font-style: italic;
        }

        .mb-2 {
            margin-bottom: 2rem !important;
        }

        .mb-3 {
            margin-bottom: 3rem !important;
        }

        .mb-4 {
            margin-bottom: 4rem !important;
        }
    </style>
</head>


<body>
    <div class="tm_container">
        <div class="tm_invoice_wrap">
            <div class="tm_invoice tm_style1 tm_type1" id="tm_download_section">
                <div class="tm_invoice_in">


                    @php
                        use Illuminate\Support\Str;
                    @endphp




                    <div class="afterHead afterHead2">



                        <p class="bb text-center"><strong>PERMANENT POSTGRADUATE STUDENT'S ACADEMIC RECORD AND
                                TRANSCRIPT</strong> </p>



                        <div class="info-container">
                            <strong>Name (Surname Last):</strong>
                            <span>{{ $biodata->Othernames && $biodata->Surname ? $biodata->Othernames . ' ' . $biodata->Surname : $biodata->name }}</span>

                            <strong>Gender:</strong>
                            <span> {{ $gender }}</span>

                            <strong>Matriculation Number:</strong>
                            <span>{{ $biodata->matric }}</span>

                            <strong>Session Admitted:</strong>
                            <span>{{ $biodata->sessionadmin ?? $results->first()->yr_of_entry }}</span>

                            <strong>Department:</strong>
                            <span>{{ $biodata->department ?? ($results->first()->department->department ?? 'N/A') }}</span>

                            <strong>Faculty:</strong>
                            <span>{{ $biodata->faculty ?? ($results->first()->faculty->faculty ?? 'N/A') }}</span>
                        </div>



                    </div>

                    <hr>
                    <div class="tm_table tm_style1">
                        <div class="overflow-x-auto">
                            <div class="watermark">
                                STUDENT'S COPY
                            </div>
                            <table class="w-full ">
                                <thead>
                                    <tr class="">
                                        <th class="">Course Code</th>
                                        <th class="">Course Title</th>
                                        <th class="">Units</th>
                                        <th class="">Status</th>
                                        <th class="">Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results as $result)
                                        <tr>
                                            <td class="text-center">
                                                {{ optional($result->course)->course_code ?? (optional($result->course)->course ?? 'N/A') }}
                                            </td>
                                            <td class="">
                                                {{ $result->course->title ?? ($result->course->course_title ?? 'N/A') }}
                                            </td>
                                            <td class="text-center">
                                                {{ $result->course->unit ?? ($result->cunit ?? 'N/A') }}
                                            </td>

                                            <td class="text-center">
                                                {{ $result->status ?? ($result->cstatus ?? 'N/A') }}
                                            </td>
                                            <td class="text-center">{{ $result->score }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <hr>
                            <p class="text-center bold"><strong>
                                    {{ $cgpa <= 7 ? 'Cumulative Grade Point Average (CGPA)' : 'Waited Average (WA)' }}
                                    Score for the
                                    Degree
                                    of Master is </strong> {{ $cgpa ?? 'N/A' }}</p>
                            <div class="test">

                                <div>
                                    <p class="text-center bold"><strong>Degree Awarded:
                                        </strong>{{ $degreeAwarded ?? 'N/A' }}</strong> </p>
                                </div>
                                <div>
                                    <p class="text-center bold"><strong>Date of Award:
                                        </strong>{{ $dateAward ?? \Carbon\Carbon::parse($results->first()->effectivedate)->format('d F, Y') }}</strong>
                                    </p>
                                </div>



                            </div>
                            <p><strong>Area of Specialization:</strong>
                                {{ $biodata->feildofinterest ?? ($results->first()->specialization->field_title ?? 'N/A') }}
                            </p>

                            <hr>

                            <div class="test mt">

                                <div class="sign">
                                    <img src="{{ asset('assets/img/ProvostSign.png') }}" alt="Not Approved"
                                        srcset="">
                                    <hr>
                                    <p>

                                        PROVOST, POSTGRADUATE COLLEGE
                                    </p>
                                </div>
                                <div class="sign">
                                    <img src="{{ asset('assets/img/DR-Transcript.png') }}" alt="Not Approved"
                                        srcset="">
                                    <hr>
                                    <p>DEPUTY REGISTRAR <br>EXAMS AND RECORDS, <br>POSTGRADUATE COLLEGE</p>
                                </div>



                            </div>

                        </div>
                    </div>



                </div>

            </div>
        </div>
        <div class="tm_invoice_btns tm_hide_print">
            <a href="javascript:window.print()" class="tm_invoice_btn tm_color1">
                <span class="tm_btn_icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                        <path
                            d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24"
                            fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                        <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none"
                            stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                        <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none"
                            stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                        <circle cx="392" cy="184" r="24" fill='currentColor' />
                    </svg>
                </span>
                <span class="tm_btn_text">Print</span>
            </a>

        </div>
    </div>
    </div>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jspdf.min.js') }}"></script>
    <script src="{{ asset('assets/js/html2canvas.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>


</body>

</html>
