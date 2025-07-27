<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Laralink">
    <!-- Site Title -->
    <title>Transcript Application | Invoices</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body,
        html {
            background: #fff !important;
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

        .bt {
            border-top: 2px solid black;

        }

        .afterHead {
            padding-top: 20rem !important;
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

        .header .title {
            text-align: center;
            margin-bottom: 2rem;
            color: #0a2b4f;
        }

        .header .title h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 900;
        }

        .header .title h2 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .header .title p {
            margin: 0;
            font-size: 1rem;
            font-style: italic;
        }

        .address {
            display: flex;
            align-items: center;
            gap: 20px;
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
            font-size: 1rem;
            font-style: italic;

        }

        .watermark {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 4rem;
            color: #0a2b4f;
            opacity: 0.08;
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

        .underline{
            text-decoration: underline;
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

                        <div class="header">
                            <div class="title">
                                <h1>UNIVERSITY OF IBADAN, IBADAN, NIGERIA</h1>
                                <h2>POSTGRADUATE COLLEGE</h2>
                                <p>https://pgcollege.ui.edu.ng/</p>
                            </div>

                            <div class="address">
                                <div class="left">
                                    <p>PROVOST:</p>
                                    <p>Prof. A.S.O. OGUNJUYIGBE,
                                        <span>D. Tech (Pretoria), R. Eng, fspsp</span>
                                    </p>
                                    <p>Mobile: <span>+234 8023504826</span></p>
                                    <p>Email: <span>aogunjuyigbe@yahoo.com, a.aogunjuyigbe@ui.edu.ng</span></p>
                                </div>
                                <div class="center">
                                    <img src="{{ asset('img/ui-logo.png') }}" alt="">
                                    <p>...Centre of excellence for postgraduate training and research</p>
                                </div>
                                <div class="right">
                                    <p>DEPUTY REGISTRAR (Examination & Records)</p>
                                    <p>MR. O.A. OLAOYE, B.A. (Ife), MMP (Ibadan), MANUPA, MCIPDM</p>
                                    <p>Mobile: <span>+234 8055265713</span></p>
                                    <p>Email: yemisiolaye6465@gmail.com</p>

                                </div>
                            </div>
                        </div>

                    <div class="watermark">
                        {{ $biodata->mark ?? 'Default...' }}
                    </div>
                    <div class="afterHead">

                        <p class="bb text-center"><strong>PERMANENT POSTGRADUATE STUDENT'S ACADEMIC RECORD AND
                                TRANSCRIPT</strong> </p>



                        <div class="info-container">
                            <strong>Name (Surname Last):</strong>
                            <span>{{ $biodata->othername && $biodata->surname ? $biodata->othername . ' ' . $biodata->surname : $biodata->name }}</span>

                            <strong>Gender:</strong>
                            <span>{{ $biodata->sex ?? $gender }}</span>

                            <strong>Matriculation Number:</strong>
                            <span>{{ $biodata->matric }}</span>

                            <strong>Session Admitted:</strong>
                            <span>{{ $biodata->yr_of_entry ?? $results->first()->yr_of_entry }}</span>

                            <strong>Department:</strong>
                            <span>{{ $biodata->department ?? ($results->first()->department->department ?? 'N/A') }}</span>

                            <strong>Faculty:</strong>
                            <span>{{ $biodata->faculty ?? ($results->first()->faculty->faculty ?? 'N/A') }}</span>
                        </div>



                    </div>

                    <hr>
                    <div class="tm_table tm_style1">
                        <div class="overflow-x-auto">
                            <table class="w-full ">
                                <thead>
                                    <tr class="">
                                        <th class="">Course Code</th>
                                        <th class="">Course Title</th>
                                        <th class="">Units</th>
                                        <th class="">Status</th>
                                        <th class="">Score(%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results as $result)
                                        <tr>
                                            <td class="text-center">{{ $result->course->course_code ?? 'N/A' }}</td>
                                            <td class="">{{ $result->course->course_title ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $result->c_unit ?? ($result->cunit ?? 'N/A') }}
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
                            <p class="text-center bold"><strong>Cumulative Grade Point Average (CGPA) Score for the
                                    Degree
                                    of Master is </strong> {{ $cgpa ?? 'N/A' }}</p>
                            <div class="test">

                                <div>
                                    <p class="text-center bold"><strong>Degree Awarded:
                                        </strong>{{ $degreeAwarded ?? 'N/A' }}</strong> </p>
                                </div>
                                <div>
                                    <p class="text-center bold"><strong>Date of Award:
                                        </strong>{{ \Carbon\Carbon::parse($results->first()->effectivedate)->format('d F, Y') }}</strong>
                                    </p>
                                </div>



                            </div>
                            <p><strong>Area of Specialization:</strong>
                                {{ $biodata->specialization ?? ($results->first()->specialization->field_title ?? 'N/A') }}
                            </p>

                            <hr>

                            <div class="test mt">

                                <div class="sign">
                                    <img src="{{ asset('img/ProvostSign.png') }}" alt="" srcset="">
                                    <hr>
                                    <p>

                                        PROVOST, POSTGRADUATE COLLEGE
                                    </p>
                                </div>
                                <div class="sign">
                                    <img src="{{ asset('img/DR-Transcript.png') }}" alt="" srcset="">
                                    <hr>
                                    <p>DEPUTY REGISTRAR <br>EXAMS AND RECORDS, <br>POSTGRADUATE COLLEGE</p>
                                </div>



                            </div>

                        </div>
                    </div>

                    <div class="page-break"></div>

                    @if (Str::contains($biodata->transInvoice->purpose, 'E-copy'))
                        <div class="letter">
                            <div class="header">
                                <div class="title">
                                    <h1>UNIVERSITY OF IBADAN, IBADAN, NIGERIA</h1>
                                    <h2>POSTGRADUATE COLLEGE</h2>
                                    <p>https://pgcollege.ui.edu.ng/</p>
                                </div>

                                <div class="address">
                                    <div class="left">
                                        <p>PROVOST:</p>
                                        <p>Prof. A.S.O. OGUNJUYIGBE,
                                            <span>D. Tech (Pretoria), R. Eng, fspsp</span>
                                        </p>
                                        <p>Mobile: <span>+234 8023504826</span></p>
                                        <p>Email: <span>aogunjuyigbe@yahoo.com, a.aogunjuyigbe@ui.edu.ng</span></p>
                                    </div>
                                    <div class="center">
                                        <img src="{{ asset('img/ui-logo.png') }}" alt="">
                                        <p>...Centre of excellence for postgraduate training and research</p>
                                    </div>
                                    <div class="right">
                                        <p>DEPUTY REGISTRAR (Examination & Records)</p>
                                        <p>MR. O.A. OLAOYE, B.A. (Ife), MMP (Ibadan), MANUPA, MCIPDM</p>
                                        <p>Mobile: <span>+234 8055265713</span></p>
                                        <p>Email: yemisiolaye6465@gmail.com</p>

                                    </div>
                                </div>
                                <div class="afterHead">

                                    <p class="bt text-center"><strong>Contact us: <span class="underline">records@pgcollege.ui.edu.ng</span></strong> </p>
                                    <div class="info-container">
                                        <p>13 June, 2024</p>
                                        <p>Visa Compliance Associate, Human Resources, Kings College, London, United Kingdom.</p>

                                        <p>Academic Transcript: {{ $biodata->othername && $biodata->surname ? $biodata->othername . ' ' . $biodata->surname : $biodata->name }}</p>
                                        <p>Matric No: {{ $biodata->matric }}</p>
                                        <p>Please find attached the official transcript/academic records of the above-named candidate.</p>
                                        <p>Please note that the transcript(s) is/are sent to you in confidence and should under no circumstances be made available to him/her for personal usage.</p>
                                        <p>Yours faithfully,</p>


                                        <p class="italic">
                                            O. A. Olaoye <br>
                                            Deputy Registrar <br>
                                            (Examinations and Records)
                                        </p>
                                    </div>



                                </div>

                            </div>
                        </div>
                    @endif
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
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jspdf.min.js"></script>
    <script src="../assets/js/html2canvas.min.js"></script>
    <script src="../assets/js/main.js"></script>


</body>

</html>
