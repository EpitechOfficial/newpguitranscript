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
         th{
            color:#fff !important;
        }
    </style>
</head>

@php

    $dateString = $invoice->yr;
    $timestamp = strtotime($dateString);
    $newTimestamp = strtotime('+5 days', $timestamp);
    $formattedExpireDate = date('F j Y', $newTimestamp);

    $formattedDate = date('F j Y', $timestamp);

@endphp

<body>
    <div class="tm_container">
        <div class="tm_invoice_wrap">
            <div class="tm_invoice tm_style1 tm_type1" id="tm_download_section">
                <div class="tm_invoice_in">
                    <div class="tm_invoice_head tm_top_head tm_mb15 tm_align_center">
                        <div class="tm_invoice_left">
                            <div class="tm_logo"><img src="../img/logo.png" alt="Logo"></div>
                        </div>
                        <div class="tm_invoice_right tm_text_right tm_mobile_hide">
                            <div class="tm_f50 tm_text_uppercase tm_white_color">Invoice</div>
                        </div>
                        <div class="tm_shape_bg tm_accent_bg tm_mobile_hide"></div>
                    </div>
                    <div class="tm_invoice_info tm_mb25">
                        <div class="tm_card_note tm_mobile_hide"></div>
                        <div class="tm_invoice_info_list tm_white_color">
                            <p class="tm_invoice_number tm_m0">Invoice No: <b>{{ $invoice->invoiceno }}</b></p>
                            <p class="tm_invoice_date tm_m0">Date: <b>{{ $formattedDate }}</b></p>
                        </div>
                        <div class="tm_invoice_seperator tm_accent_bg"></div>
                    </div>
                    <div class="tm_invoice_head tm_mb10">
                        <div class="tm_invoice_left">
                            <p class="tm_mb2 tm_f16"><b class="tm_primary_color tm_text_uppercase">The Postgraduate
                                    College,</br>University of Ibadan,</br>Ibadan, Nigeria.</b></p>

                        </div>
                        <div class="tm_invoice_right">
                            <div class="tm_grid_row tm_col_3  tm_col_2_sm tm_invoice_table tm_round_border">
                                <div>
                                    <p class="tm_m0">Student Name:</p>
                                    <b class="tm_primary_color">{{$fullName}}</b>
                                </div>
                                <div>
                                    <p class="tm_m0">Student Matric:</p>
                                    <b class="tm_primary_color">{{ $invoice->appno }}</b>
                                </div>
                                <div>
                                    <p class="tm_m0">Invoice for:</p>
                                    <b class="tm_primary_color">Transcript</b>
                                </div>

                            </div>
                        </div>
                    </div>
                    @php
                        $purpose_array = $invoice->purpose;
                        $purpose = explode(',', $purpose_array);
                        array_pop($purpose);
                        $length = count($purpose);

                        $copy_array = $invoice->mth;
                        $copy = explode(',', $copy_array);
                        array_pop($copy);

                        $amount_array = $invoice->dy;
                        $amounts = explode(',', $amount_array);
                        array_pop($amounts);

                    @endphp
                    <div class="tm_table tm_style1">
                        <div >
                            <div class="tm_table_responsive">
                                <table>
                                    <thead>
                                        <tr class="tm_accent_bg">
                                            <th >Item</th>
                                            <th >Description</th>
                                            <th >Price</th>
                                            <th >Copy</th>
                                            <th >Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < $length; $i++)
                                            <tr>
                                                <td >{{ $i + 1 }}</td>
                                                <td >{{ $purpose[$i] }}</td>
                                                <td >₦ {{ number_format($amounts[$i]) }}</td>
                                                <td >{{ $copy[$i] }}</td>
                                                <td >₦
                                                    {{ number_format($amounts[$i] * $copy[$i]) }}</td>
                                            </tr>
                                        @endfor

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tm_right_footer">
                            <table class="tm_mb15">
                                <tbody>

                                    <tr class="tm_accent_bg">
                                        <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_white_color">Grand Total
                                        </td>
                                        <td
                                            class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_white_color tm_text_right">
                                            ₦ {{ number_format($invoice->amount_charge) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="button-invoice">

                  <div class="edit">
                    <a href="{{ route('transinvoice.edit', ['transinvoice' => $invoice->id]) }}" class="warning">Edit Invoice</a>
                </div>
                
                

                  <div class="payment">
                    <a href='#' class='primary'>Proceed to Payment</a>
                  </div>
                </div>
                <div class="tm_note tm_text_center tm_font_style_normal">
                    <hr class="tm_mb15">
                    <p class="tm_mb2"><b class="tm_primary_color">Terms & Conditions:</b></p>
                    <p class="tm_m0">Payment should be made <b class="tm_primary_color">On</b> or <b
                            class="tm_primary_color">Before</b> {{ $formattedExpireDate }}.</p>
                </div><!-- .tm_note -->
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
            <button id="tm_download_btn" class="tm_invoice_btn tm_color2">
                <span class="tm_btn_icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                        <path
                            d="M320 336h76c55 0 100-21.21 100-75.6s-53-73.47-96-75.6C391.11 99.74 329 48 256 48c-69 0-113.44 45.79-128 91.2-60 5.7-112 35.88-112 98.4S70 336 136 336h56M192 400.1l64 63.9 64-63.9M256 224v224.03"
                            fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="32" />
                    </svg>
                </span>
                <span class="tm_btn_text">Download</span>
            </button>
        </div>
    </div>
    </div>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jspdf.min.js"></script>
    <script src="../assets/js/html2canvas.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>

</html>
