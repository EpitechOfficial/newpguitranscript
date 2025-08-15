<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Laralink">
    <title>Transcript Application | Letter</title>
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

        .sign2 img {
            width: 120px;
            border-bottom: 2px solid black;
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
    </style>
</head>

<body>
    <div class="letter">
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
                        <p><span class="bold"> Prof. A.S.O. OGUNJUYIGBE,</span> D. Tech (Pretoria), R. Eng, fspsp</p>
                        <p><span class="bold">Mobile:</span> +234 8023504826</p>
                        <p><span class="bold">Email:</span> aogunjuyigbe@yahoo.com, a.aogunjuyigbe@ui.edu.ng</p>
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
                        <p><span class="bold">MR. O.A. OLAOYE,</span> B.A. (Ife), MMP (Ibadan), MANUPA, MCIPDM</p>
                        <p><span class="bold">Mobile:</span> +234 8055265713</p>
                        <p><span class="bold">Email:</span> yemisiolaye6465@gmail.com</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="afterHead">
            <p class="bt text-center"><strong>Contact us: <span
                        class="underline">records@pgcollege.ui.edu.ng</span></strong> </p>
            <div>
                <p class="bold mb-3">13 June, 2024</p>
                <p class="add-width mb-4">{{ $biodata->ecopy_address ?? 'N/A' }}</p>
                <p class="bold">Academic Transcript:
                    {{ $biodata->Othernames && $biodata->Surname ? $biodata->Othernames . ' ' . $biodata->Surname : $biodata->name }}
                </p>
                <p> <span class="bold">Matric No:</span> {{ $biodata->matric }}</p>
                <p class="bold">Please find attached the official transcript/academic records of the above-named
                    candidate.</p>
                <p>Please note that the transcript(s) is/are sent to you in confidence and should under no circumstances
                    be made available to him/her for personal usage.</p>
                <p class="mb-4">Yours faithfully,</p>
                <div class="sign2">
                    <img src="{{ isset($forPdf) && $forPdf ? public_path('assets/img/DR-Transcript.png') : asset('assets/img/DR-Transcript.png') }}"
                        alt="">
                    <p class="italic">
                        O. A. Olaoye <br>
                        Deputy Registrar <br>
                        (Examinations and Records)
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
