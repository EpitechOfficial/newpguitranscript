<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Laralink">
    <title>Transcript Application | Details</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
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

        .afterHead {
            padding-top: 2rem !important;
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
    </style>
</head>
<body>
<div class="tm_container">
    <div class="tm_invoice_wrap">
        <div class="tm_invoice tm_style1 tm_type1" id="tm_download_section">
            <div class="tm_invoice_in">
                <div class="afterHead">
                    <p class="bb text-center"><strong>TRANSCRIPT APPLICATION DETAILS</strong></p>
                    <div class="info-container">
                        <strong>Name (Surname Last):</strong>
                        <span>{{ $record->Othernames . ' ' . $record->Surname }}</span>
                        <strong>Title:</strong>
                        <span>{{ $record->tittle }}</span>
                        <strong>Gender:</strong>
                        <span>{{ $record->sex }}</span>
                        <strong>Matriculation Number:</strong>
                        <span>{{ $record->matric }}</span>
                        <strong>Maiden Name:</strong>
                        <span>{{ $record->maiden }}</span>
                        <strong>Session Admitted:</strong>
                        <span>{{ $record->sessionadmin }}</span>
                        <strong>Session Graduated:</strong>
                        <span>{{ $record->sessiongrad }}</span>
                        <strong>Department:</strong>
                        <span>{{ $record->department }}</span>
                        <strong>Faculty:</strong>
                        <span>{{ $record->faculty }}</span>
                        <strong>Degree:</strong>
                        <span>{{ $record->degree }}</span>
                        <strong>Specialization:</strong>
                        <span>{{ $record->feildofinterest }}</span>
                    </div>
                </div>
                <hr>
                <div class="tm_table tm_style1">
                    <div class="info-container">
                     <strong>Transcript Type:</strong>
                        <span>{{ $record->transInvoice->purpose ?? 'N/A' }}</span>
                        <strong>Amount:</strong>
                        <span>{{ $record->transInvoice->dy ?? 'N/A' }}</span>
                        <strong>Number of Copies:</strong>
                        <span>{{ $record->transInvoice->mth ?? 'N/A' }}</span>
                        <strong>E-Copy Destination Email:</strong>
                        <span>{{ $ecopy->ecopy_email ?? 'N/A' }}</span>
                        <strong>E-Copy Destination Address:</strong>
                        <span>{{ $ecopy->ecopy_address ?? 'N/A' }}</span>
                        <strong>Email:</strong>
                        <span>{{ $record->getPrimaryCourier()->email ?? 'N/A' }}</span>
                        <strong>Phone:</strong>
                        <span>{{ $record->getPrimaryCourier()->phone ?? 'N/A' }}</span>
                        
                        @if($record->couriers->count() > 0)
                            <strong>All Courier Destinations:</strong>
                            <span>
                                @foreach($record->couriers as $index => $courier)
                                    <div style="margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                                        <strong>Copy {{ $index + 1 }}:</strong><br>
                                        <strong>Dispatch Mode:</strong> {{ $courier->courier_name ?? 'N/A' }}<br>
                                        <strong>Transcript Type:</strong> {{ $courier->transcript_purpose ?? 'N/A' }}<br>
                                        <strong>Number of Copies:</strong> {{ $courier->number_of_copies ?? '1' }}<br>
                                        <strong>Country:</strong> {{ $courier->destination }}<br>
                                        <strong>Address:</strong> {{ $courier->address }}<br>
                                        @if($courier->address2)
                                            <strong>Address 2:</strong> {{ $courier->address2 }}<br>
                                        @endif
                                    </div>
                                @endforeach
                            </span>
                        @endif
                        
                        <strong>Date Requested:</strong>
                        <span>{{ $record->date_requested }}</span>
                        <strong>Status:</strong>
                        <span>{{ $record->status }}</span>
                        <strong>Uploaded File:</strong>
                        <span>
                        @if ($record->file && $record->file->file_path)
                                            <a class="btn btn-primary btn-sm" href="{{ config('app.url') }}/storage/{{ $record->file->file_path }}" target="_blank">View File</a>
                                        @else
                                            N/A
                                        @endif</span>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="tm_invoice_btns tm_hide_print">
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
    </div> --}}
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/jspdf.min.js') }}"></script>
<script src="{{ asset('assets/js/html2canvas.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

</body>
</html>
