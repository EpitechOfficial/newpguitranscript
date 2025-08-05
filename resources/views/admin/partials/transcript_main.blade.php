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
    <style>
        body,
        html {
            background: #fff !important;
            font-family: 'Montserrat', sans-serif;
        }

        th {
            text-align: center !important;
        }

        td {
            border: none !important;
            padding-bottom: 0 !important;
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

        .afterHead {}

        .info-container {
            max-width: 600px;
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
            font-size: 1rem;
            font-weight: 900;
            color: #0a2b4f !important;
        }

        .header .title h2 {
            margin: 0;
            font-size: 0.75rem;
            font-weight: 600;
            color: #0a2b4f !important;
        }

        .header .title p {
            margin: 0;
            font-size: 0.5rem;
            font-style: italic;
            font-weight: 600;
        }

        .underline {
            text-decoration: underline;
        }

        .bold {
            font-weight: 700 !important;
        }

        .add-width {
            width: 33.33%;
        }

        .italic {
            font-style: italic;
        }

        .address-table td {
            vertical-align: top;
            padding: 0 10px;
        }

        .address-table .center {
            text-align: center;
        }

        .address-table .center img {
            width: 6rem;
            margin-bottom: 0;
        }

        .address-table .center p {
            text-align: center;
            font-size: 0.5rem;
            margin: 0;
            font-style: italic;
        }

        .address-table .right p,
        .address-table .left p {
            margin-bottom: 0;
            margin-top: 0;
        }

        .bt {
            border-top: 2px solid black;
        }

        .mb-3 {
            margin-bottom: 3rem;
        }

        .mb-4 {
            margin-bottom: 4rem;
        }

        .tm_table th,
        .tm_table td {
            font-size: 0.8rem;
        }

        .tm_table th {
            font-weight: 700;
        }

        .tm_table td {
            font-weight: 400;
        }

        /* DomPDF-compatible Results table styling */
        .results-table {
            width: 100%;
            border-collapse: collapse;
        }

        .results-table th,
        .results-table td {
            padding: 5px 10px;
            border: none;
            text-align: center;
            vertical-align: top;
        }


        .course-code-col {
            width: 15%;
            text-align: center;
        }

        .course-title-col {
            width: 50%;
            text-align: left;
        }

        .units-col {
            width: 10%;
            text-align: center;
        }

        .status-col {
            width: 10%;
            text-align: center;
        }

        .score-col {
            width: 15%;
            text-align: center;
        }

        .tm_invoice_in {
            position: relative;
        }

        .watermark-light {
            position: absolute;
            top: 45%;
            left: 50%;
            font-size: 40px;
            color: #f0f0f0;
            font-weight: bold;
            white-space: nowrap;
            margin-left: -150px;
            margin-top: -20px;
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
                        <table width="100%" class="address-table" style="margin-bottom: 1rem;">
                            <tr>
                                <td class="left" style="width:33%; vertical-align:top;">
                                    <p class="bold">PROVOST:</p>
                                    <p><span class="bold"> Prof. A.S.O. OGUNJUYIGBE,</span> D. Tech (Pretoria), R.
                                        Eng, fspsp</p>
                                    <p><span class="bold">Mobile:</span> +234 8023504826</p>
                                    <p><span class="bold">Email:</span> aogunjuyigbe@yahoo.com,
                                        a.aogunjuyigbe@ui.edu.ng</p>
                                </td>
                                <td class="center" style="width:34%; vertical-align:top; text-align:center;">
                                    @if (isset($forPdf) && $forPdf)
                                        <img src="{{ public_path('img/ui-logo2.png') }}" alt="">
                                    @else
                                        <img src="{{ asset('img/ui-logo2.png') }}" alt="">
                                    @endif
                                    <p>...Centre of excellence for <br> postgraduate training and research</p>
                                </td>
                                <td class="right" style="width:33%; vertical-align:top;">
                                    <p><span class="bold">DEPUTY REGISTRAR </span> <br>(Examination & Records)</p>
                                    <p><span class="bold">MR. O.A. OLAOYE,</span> B.A. (Ife), MMP (Ibadan), MANUPA,
                                        MCIPDM</p>
                                    <p><span class="bold">Mobile:</span> +234 8055265713</p>
                                    <p><span class="bold">Email:</span> yemisiolaye6465@gmail.com</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    @if ($biodata->ecopy_email)
                        <div class="watermark">
                            {{ $biodata->ecopy_address ?? 'Default...' }}
                        </div>
                    @else
                        <div class="watermark">
                            {{ $biodata->getPrimaryCourier()?->address ?? 'Default...' }}
                        </div>
                    @endif
                    <div class="afterHead">
                        <p class="bb text-center"><strong>PERMANENT POSTGRADUATE STUDENT'S ACADEMIC RECORD AND
                                TRANSCRIPT</strong></p>
                        <table class="info-container"
                            style="width:100%; margin-bottom:2rem; margin-left:auto; margin-right:auto;">
                            <tr>
                                <td style="font-weight:bold; text-align:left;">Name (Surname Last):</td>
                                <td>{{ $biodata->Othernames && $biodata->Surname ? $biodata->Othernames . ' ' . $biodata->Surname : $biodata->name }}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold; text-align:left;">Gender:</td>
                                <td>{{ $biodata->sex ?? $gender }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold; text-align:left;">Matriculation Number:</td>
                                <td>{{ $biodata->matric }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold; text-align:left;">Session Admitted:</td>
                                <td>{{ $biodata->sessionadmin ?? $results->first()->yr_of_entry }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold; text-align:left;">Department:</td>
                                <td>{{ $biodata->department ?? ($results->first()->department->department ?? 'N/A') }}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold; text-align:left;">Faculty:</td>
                                <td>{{ $biodata->faculty ?? ($results->first()->faculty->faculty ?? 'N/A') }}</td>
                            </tr>
                        </table>
                    </div>
                    <hr>
                    <div class="tm_table tm_style1">
                        <div class="overflow-x-auto">
                            <table class="results-table">
                                <thead>
                                    <tr>
                                        <th class="course-code-col">Course Code</th>
                                        <th class="course-title-col">Course Title</th>
                                        <th class="units-col">Units</th>
                                        <th class="status-col">Status</th>
                                        <th class="score-col">Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results as $result)
                                        <tr>
                                            <td class="course-code-col">
                                                {{ optional($result->course)->course_code ?? (optional($result->course)->course ?? 'N/A') }}
                                            </td>
                                            <td class="course-title-col">
                                                {{ $result->course->title ?? ($result->course->course_title ?? 'N/A') }}
                                            </td>
                                            <td class="units-col">
                                                {{ $result->course->unit ?? ($result->cunit ?? 'N/A') }}</td>
                                            <td class="status-col">{{ $result->status ?? ($result->cstatus ?? 'N/A') }}
                                            </td>
                                            <td class="score-col">{{ $result->score }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <hr>
                            <p class="text-center bold">
                                <strong>{{ $cgpa <= 7 ? 'Cumulative Grade Point Average (CGPA)' : 'Waited Average (WA)' }}
                                    Score for the Degree of Master is </strong> {{ $cgpa ?? 'N/A' }}</p>
                            <table width="100%" style="margin-top: 1rem;">
                                <tr>
                                    <td style="width:50%; text-align:center;">
                                        <p class="text-center bold"><strong>Degree
                                                Awarded:</strong>{{ $degreeAwarded ?? 'N/A' }}</p>
                                    </td>
                                    <td style="width:50%; text-align:center;">
                                        <p class="text-center bold"><strong>Date of
                                                Award:</strong>{{ $dateAward ?? \Carbon\Carbon::parse($results->first()->effectivedate)->format('d F, Y') }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            <p><strong>Area of Specialization:</strong>
                                {{ $biodata->specialization ?? ($results->first()->specialization->field_title ?? 'N/A') }}
                            </p>
                            <hr>
                            <table width="100%" style="margin-top: 1rem;">
                                <tr>
                                    <td style="width:50%; text-align:center; vertical-align:top;">
                                        <div class="sign">
                                            <img src="{{ isset($forPdf) && $forPdf ? public_path('assets/img/ProvostSign.png') : asset('assets/img/ProvostSign.png') }}"
                                                alt="">
                                            <p>PROVOST, POSTGRADUATE COLLEGE</p>
                                        </div>
                                    </td>
                                    <td style="width:50%; text-align:center; vertical-align:top;">
                                        <div class="sign">
                                            <img src="{{ isset($forPdf) && $forPdf ? public_path('assets/img/DR-Transcript.png') : asset('assets/img/DR-Transcript.png') }}"
                                                alt="">
                                            <p>DEPUTY REGISTRAR <br>EXAMS AND RECORDS, <br>POSTGRADUATE COLLEGE</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
