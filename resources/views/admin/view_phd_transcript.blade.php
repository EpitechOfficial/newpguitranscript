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
            font-size: 1.15rem !important;
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

        .sign2 img {
            width: 220px;
            border-bottom: 2px solid black;
            display: flex;
            
        }

        .sign2 {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            text-align: center;
            margin-top: -1rem;
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

        .small_result td {
            padding: 0 !important;
        }



        .afterHead2 {
            padding-top: 8rem !important;
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

        .small_result {
            width: 70%;
            font-size: 0.55rem;
            margin: 0 auto;
            padding: 0;
        }

        .alignLeft {
            text-align: left;
            display: flex;
            justify-content: flex-end;
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
                        <div class="watermark">
                            STUDENT'S COPY
                        </div>


                        <div>
                            <p class="bold mb-3 alignLeft">{{ \Carbon\Carbon::now()->format('d F, Y') }}</p>
                            <p class="add-width mb-4">Student's Copy</p>

                            <p class=" text-center">
                                <strong>{{ $biodata->Othernames && $biodata->Surname ? $biodata->Othernames . ' ' . $biodata->Surname : $biodata->name }}</strong>
                            </p>
                            <p class=" text-center">(MATRIC NO. S.I. {{ $biodata->matric }}) - AWARD OF
                                {{ $biodata->degree ?? 'N/A' }} DEGREE</p>
                            
                            <p>This is to certify that the above named was awarded the degree of
                                <strong>{{ $biodata->degree == 'Ph.D' ? 'Doctor of Philosophy (Ph.D)' : 'Master of Philosophy (M.Phil)' }}

                                </strong>from the <em> Department of {{ $biodata->department ?? 'N/A' }}</em>,
                                Faculty
                                of {{ $biodata->faculty ?? 'N/A' }}, University of Ibadan, Ibadan, Nigeria on
                                <strong>{{ $dateAward }}</strong>.
                            </p>

                            @if ($results->isNotEmpty())
                                <div class="tm_table tm_style1 small_result">

                                    <p>Below is a list of the courses the candidate offered and passed</p>
                                    <div class="overflow-x-auto">
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
                                                Cumulative Grade Point Average (CGPA) Score for the
                                                Degree is </strong> {{ $cgpa ?? 'N/A' }}</p>



                                        <hr>


                                    </div>
                                </div>

                            @endif

                            <p>The title of {{ $gender ? ($gender == 'Male' ? 'his' : 'her') : 'N/A' }}
 Thesis was:</p>

                            <p class="bold italic mb-2">"{{ $biodata->thesis_title ?? 'N/A' }}".</p>

                            @if ($results->isEmpty())
                                <p>The programme was not assessed through course work but through thesis hence an
                                    academic
                                    transcript cannot be issued.</p>
                            @endif
                            <p class="mb-4">I hope you will find the above information useful.</p>

                            <div class="sign2">
                                <img src="{{ asset('assets/img/DR-Transcript.png') }}" alt="Not Approved"
                                    srcset="">
                                <p class="italic">
                                    O. A. Olaoye <br>
                                    Deputy Registrar <br>
                                    (Examinations and Records)
                                </p>
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
