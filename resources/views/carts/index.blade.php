<x-app-layout :pageName="'Cart'">


        <style>
            .btn-foreground-alternate {

                background: #dc3545 !important;
                color: #fff !important;
                border-radius: 0.2rem !important;
                width: fit-content !important;

            }

            #hiddenForm {
                display: none;
            }

            .cardEmpty {
                display: flex !important;
                background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), url('img/cart.png') center center no-repeat;
                justify-content: center !important;
                align-items: center !important;
                height: 50vh !important;


            }

            .card .empty {
                font-size: 2rem;

            }

            .cartBotton {
                display: flex !important;
                flex-direction: row !important;
                justify-content: space-between !important;
            }
        </style>







    <div class="container">
        <!-- Title and Top Buttons Start -->

        <div class="page-title-container">
            <div class="row cartBotton">
                <!-- Title Start -->
                <div class="col-6 col-md-3 ">
                    <h1 class="mb-0 pb-0 display-4" id="title">Cart</h1>

                </div>

                @if ($cartItems->isNotEmpty() && $transDetails->isEmpty())
                    <div class="col-6 col-md-3">

                        <a href="{{ route('dashboard.create') }}" class="btn btn-primary">Add More Items</a>
                    </div>
                @elseif ($cartItems->isNotEmpty() && $transDetails->isNotEmpty())
                    <div class="col-6 col-md-3">

                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Add More Items</a>
                    </div>
                @endif

                <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->

        <!-- Content Start -->

        <div class="row">
            <div class="col-12 col-lg order-1 order-lg-0">
                <!-- Items Start -->
                <h2 class="small-title">Items</h2>
                <div class="mb-5">
                    @if ($cartItems->isEmpty() )

                        <div class="card cardEmpty mb-2">
                            <p class="empty">Your Cart is Empty</p>
                            @if ($transDetails->isEmpty())
                            <a class="btn btn-center btn-icon btn-icon-end btn-primary width-10"
                                href="{{ route('dashboard.create') }}">
                                <span>Apply Now!</span>
                                <i data-acorn-icon="chevron-right"></i>
                            </a>
                            @else
                            <a class="btn btn-center btn-icon btn-icon-end btn-primary width-10"
                                href="{{ route('dashboard') }}">
                                <span>Apply Now!</span>
                                <i data-acorn-icon="chevron-right"></i>
                            </a>
                            @endif
                        </div>
                    @else
                        @php
                            $total = 0;
                            $purpose = '';
                            $num_copies = '';
                            $fee_each = '';
                            $date = date('Y-m-d h:i:s');
                            $invoiceno = rand(10000, 99999999);

                        @endphp
                        @foreach ($cartItems as $item)
                            @php
                                $total += $item->fee * $item->num_copies;
                                $purpose .= $item->request;
                                $purpose .= ', ';

                                $fee_each .= $item->fee;
                                $fee_each .= ', ';

                                $num_copies .= $item->num_copies;
                                $num_copies .= ', ';

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
                                                <div class="col-12 col-md-6 mb-2 mb-md-0 d-flex align-items-center">
                                                    <div class="pt-0 pb-0 pe-2">
                                                        <div class="h6 mb-0 clamp-line" data-line="1">
                                                            {{ $item->request }}</div>
                                                        <div class="text-muted text-small">
                                                            {{ $item->request_type }}</div>
                                                        <div class="mb-0 sw-19">₦
                                                            {{ number_format($item->fee) }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 pe-0 d-flex align-items-center">
                                                    <div class="h6 mb-0">{{ $item->num_copies }} Copies</div>
                                                </div>
                                                <div
                                                    class="col-6 col-md-3 d-flex justify-content-end justify-content-md-start align-items-center">
                                                    <div class="h6 mb-0">₦
                                                        {{ number_format($item->fee * $item->num_copies) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="btn btn-sm btn-icon btn-icon-only btn btn-foreground-alternate position-absolute t-2 e-4"
                                                type="submit"
                                                onclick="return confirm('Are you sure you want to remove this Item?');">
                                                Remove <i data-acorn-icon="error-hexagon"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                </div>
                <!-- Items End -->

            </div>

            <div class="col-12 col-lg-auto order-0 order-lg-1">
                <!-- Summary Start -->
                <h2 class="small-title">Summary</h2>
                <div class="card mb-5 w-100 sw-lg-35">
                    <div class="card-body">
                        <div class="mb-4">

                            <div class="mb-2">
                                <p class="text-medium text-muted mb-1">ITEMS:&nbsp;&nbsp;<span
                                        class="text-alternate">{{ $cartItemCount }}</span></p>

                            </div>
                            <div class="mb-2">
                                <p class="text-medium text-muted mb-1">Total Amount:&nbsp;&nbsp;<span
                                        class="text-alternate">₦ {{ number_format($total) }}</span></p>

                            </div>

                        </div>
                        <form method="post" action="{{ route('transinvoice.store') }}">
                            @csrf
                            <div id="hiddenForm">
                                <input type="text" name="appno" value="{{ $item->matric }}">
                                <input type="text" name="invoiceno" value="{{ $invoiceno }}">
                                <input type="text" name="purpose" value="{{ $purpose }}">
                                <input type="text" name="mth" value="{{ $num_copies }}">
                                <input type="text" name="dy" value="{{ $fee_each }}">
                                <input type="text" name="yr" value="{{ $date }}">
                                <input type="text" name="amount_charge" value="{{ $total }}">
                                <input type="text" name="amount_paid" value="{{ 0 }}">
                            </div>
                            <button class="btn btn-icon btn-icon-end btn-primary w-100" type="submit">
                                <span>Checkout</span>
                                <i data-acorn-icon="chevron-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <!-- Summary End -->
            </div>
            @endif
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
