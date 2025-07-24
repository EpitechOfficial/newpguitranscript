<x-app-layout :pageName="'EditInvoice'">

    <style>
        .btn-foreground-alternate {

            background: #439b38 !important;
            color: #fff !important;
            border-radius: 0.2rem !important;
            width: fit-content !important;

        }

        #hiddenForm {
            display: none;
        }

        .t-2 {
            top: 2.5rem !important;
            z-index: 1;
        }

        .e-4 {
            right: 1.5rem !important;
            z-index: 1;
        }

        .clamp-line {
            font-weight: bold !important;
        }

        .left {
            color: red;
            font-weight: bold;
        }
        .gr {
            color: green;
            font-weight: bold;
        }
        @media (max-width: 767.98px) {
  .pur{
    font-size: 70% !important;
    margin: 0 !important;
    padding: 0 !important;
  }
  .card-body{
    padding-top: 0.2rem !important;
    padding-bottom: 0.2rem !important;
  }
  .t-2 {
            top: 1.5rem !important;
        }
}
.cardEmpty {
            display: flex !important;
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), url('img/no-invoice.png') center center no-repeat;
            justify-content: center !important;
            align-items: center !important;
            height: 50vh !important;


        }

        .card .empty {
            font-size: 2rem;

        }
        .btn-mine{
            background: #0a2b4f !important;
        }
   </style>




            <div class="container">
                <!-- Title and Top Buttons Start -->

                <div class="page-title-container">
                    <div class="row">
                        <!-- Title Start -->
                        <div class="col-12 col-md-7">
                            <h1 class="mb-0 pb-0 display-4" id="title">Invoices</h1>
                            <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">

                            </nav>
                        </div>
                        <!-- Title End -->
                    </div>
                </div>
                <!-- Title and Top Buttons End -->

                <!-- Content Start -->

                <div class="row">
                    <div class="col-12 col-lg order-1 order-lg-0">
                        <!-- Items Start -->
                        <h2 class="small-title">Items</h2>
                        @if ($invoices->isEmpty())

                                <div class="card cardEmpty mb-2">
                                    <p class="empty">You have no active invoice</p>
                                    <button class="btn btn-center btn-icon btn-icon-end btn-primary w-10" href="#">
                                        <span>Apply Now!</span>
                                        <i data-acorn-icon="chevron-right"></i>
                                    </button>
                                </div>
                            @else
                        <div class="mb-5">
                            @foreach ($invoices as $invoice)
                                @php
                                    $timestampString = $invoice->yr;

                                    $timestamp = strtotime($timestampString);
                                    $timestampPlus5 = strtotime('+5 days', $timestamp);

                                    $now = date('Y-m-d h:i:s');
                                    $newTimestamp = strtotime($now);
                                    $difference = $timestampPlus5 - $newTimestamp;
                                    $daysLeft = floor($difference / (60 * 60 * 24));
                                    $hoursLeft = floor($difference / (60 * 60));
                                    $minutesLeft = floor(($difference % (60 * 60)) / 60);

                                    // $amountCharge = $invoice->amount_charge
                                    // $amountCharge = $invoice->amount_charge

                                @endphp

                                <div class="card mb-2">
                                    <div class="row g-0 sh-18 sh-md-14">
                                        <div class="col-auto">
                                            <img src="img/transcript.jpg"
                                                class="card-img card-img-horizontal h-100 sw-9 sw-sm-13 sw-md-15"
                                                alt="thumb" />
                                        </div>

                                        <div class="col position-relative h-100">
                                            <div class="card-body">
                                                <div class="row h-100">
                                                    <div class="col-6 col-md-3 mb-2 mb-md-0 d-flex align-items-center">
                                                        <div class="pt-0 pb-0 pe-2">
                                                            <div class="h6 mb-0 clamp-line" data-line="1">Invoice
                                                                Number:</div>

                                                            <div class="mb-0 sw-19 pur">{{ $invoice->invoiceno }}</div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="col-12 col-md-6 mb-2 mb-md-0 d-flex align-items-center">
                                                        <div class="pt-0 pb-0 pe-2">
                                                            <div class="h6 mb-0 clamp-line" data-line="1">Purpose:
                                                            </div>

                                                            <div class="mb-0 pur">{{ $invoice->purpose }}</div>
                                                        </div>
                                                    </div>
                                                    @if ($invoice->amount_charge != $invoice->amount_paid)
                                                    <div class="col-3 col-md-1 d-flex align-items-center">
                                                        <div class="pt-0 pb-0 pe-2">
                                                            <div class="h6 mb-0 clamp-line" data-line="1">Expires:
                                                            </div>

                                                            <div class="mb-0 sw-19 left pur">@if (($daysLeft != 0) )

                                                                {{ $daysLeft."Day(s) Left" }}
                                                            @elseif(($daysLeft == 0) && ($hoursLeft != 0))
                                                            {{ $hoursLeft."Hour(s) ".$minutesLeft."Minute(s) Left" }}
                                                            @else
                                                            {{ $minutesLeft." Minute(s) Left" }}
                                                            @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @else
                                                    <div class="mb-0 sw-19 gr pur">Paid</div>
                                                    @endif

                                                </div>
                                            </div>

                                                    @if ($invoice->amount_charge != $invoice->amount_paid)


                                            <form action="{{ route('transinvoice.show', $invoice->id) }}"
                                                method="GET" target="_blank">
                                                @csrf

                                                <button
                                                    class="btn btn-sm btn-icon btn-icon-only btn btn-foreground-alternate btn-mine position-absolute t-2 e-4"
                                                    type="submit">
                                                    view Invoice <i data-acorn-icon="eye"></i>
                                                </button>
                                            </form>
                                            @else
<a href="{{ route('transinvoice.showReceipt', ['id' => $invoice->id]) }}">
                                            <button
                                                   class="btn btn-sm btn-icon btn-icon-only btn btn-foreground-alternate btn-min position-absolute t-2 e-4"
                                                   type="submit">
                                                    view Receipt <i data-acorn-icon="eye"></i>
                                                </button>
                                            </a>

                                             {{-- <form action="{{ route('transinvoice.showReceipt', $invoice->id) }}"
                                                method="GET" target="_blank">
                                                @csrf

                                                <button
                                                    class="btn btn-sm btn-icon btn-icon-only btn btn-foreground-alternate btn-primary position-absolute t-2 e-4"
                                                    type="submit">
                                                    view Receipt <i data-acorn-icon="eye"></i>
                                                </button>
                                            </form> --}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                        <!-- Items End -->
                        @endif
                    </div>

                </div>

                <!-- Content End -->
            </div>


    </div>



    <!-- Vendor Scripts Start -->
    <script src="js/vendor/jquery-3.5.1.min.js"></script>
    <script src="js/vendor/bootstrap.bundle.min.js"></script>
    <script src="js/vendor/OverlayScrollbars.min.js"></script>
    <script src="js/vendor/autoComplete.min.js"></script>
    <script src="js/vendor/clamp.min.js"></script>

    <script src="icon/acorn-icons.js"></script>
    <script src="icon/acorn-icons-interface.js"></script>

    <!-- Vendor Scripts End -->

    <!-- Template Base Scripts Start -->
    <script src="js/base/helpers.js"></script>
    <script src="js/base/globals.js"></script>
    <script src="js/base/nav.js"></script>
    <script src="js/base/search.js"></script>
    <script src="js/base/settings.js"></script>
    <!-- Template Base Scripts End -->
    <!-- Page Specific Scripts Start -->

    <script src="js/common.js"></script>
    <script src="js/scripts.js"></script>
    <!-- Page Specific Scripts End -->
</body>

</html>
</x-app-layout>
