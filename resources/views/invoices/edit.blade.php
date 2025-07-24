<x-app-layout :pageName="'EditInvoice'">


    <style>
        .purpose {
            padding: 0.5rem;
            outline: none;
            width: 20vw !important;

        }

        .copy {
            padding: 0.5rem;
            outline: none;

        }

        .copy1,
        .amount,
        .total {
            padding: 0.5vw;
            outline: none;
            border: none !important;

        }
        .btn-center{
            margin: 0 20vw 2rem 20vw;
        }
       
    </style>




  
            <div class="container">
                <!-- Title and Top Buttons Start -->
                <div class="page-title-container">
                    <div class="row">
                        <!-- Title Start -->
                        <div class="col-12 col-md-7">
                            <h1 class="mb-0 pb-0 display-4" id="title">Edit Invoices</h1>
                            <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                            </nav>
                        </div>
                        <!-- Title End -->
                    </div>
                </div>
                <!-- Title and Top Buttons End -->
        
                <!-- Content Start -->
                <section class="scroll-section" id="basic">
                    <div class="card mb-5">
                        <form method="post" action="{{ route('transinvoice.update', ['transinvoice' => $invoice->id]) }}">
                            @method('PATCH')
                            @csrf
                        
                            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                        
                            <div class="card-body">
                                <p class="tm_invoice_number tm_m0">Invoice No: <b>{{ $invoice->invoiceno }}</b></p>
                                <div class="table-responsive">

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Description</th>
                                            <th>Price (₦)</th>
                                            <th>Copy(ies)</th>
                                            <th>Total (₦)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $purpose_array = explode(',', rtrim($invoice->purpose, ','));
                                            $amount_array = explode(',', rtrim($invoice->dy, ','));
                                            $copy_array = explode(',', rtrim($invoice->mth, ','));
                                            $length = count($purpose_array);
                                        @endphp
                        
                                        @for ($i = 0; $i < $length; $i++)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>
                                                    <select name="transactions[{{$i}}][purpose]" class="purpose">
                                                        <option value="{{ $purpose_array[$i] }}" selected disabled>{{ $purpose_array[$i] }}</option>
                                                        @foreach ($requests as $request)
                                                            <option value="{{ $request->requesttype }}" data-amount="{{ $request->amount }}">
                                                                {{ $request->requesttype }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="transactions[{{$i}}][original_purpose]" value="{{ $purpose_array[$i] }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="amount" name="transactions[{{$i}}][amount]" value="{{ $amount_array[$i] }}" readonly>
                                                    <input type="hidden" name="transactions[{{$i}}][original_amount]" value="{{ $amount_array[$i] }}">
                                                </td>
                                                <td>
                                                    <select name="transactions[{{$i}}][copy]" class="copy">
                                                        <option value="{{ $copy_array[$i] }}" selected disabled>{{ $copy_array[$i] }}</option>
                                                        @for ($j = 1; $j <= 10; $j++)
                                                            <option value="{{ $j }}">{{ $j }}</option>
                                                        @endfor
                                                    </select>
                                                    <input type="hidden" name="transactions[{{$i}}][original_copy]" value="{{ $copy_array[$i] }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="transactions[{{$i}}][total]" class="total" value="{{ $amount_array[$i] * $copy_array[$i] }}" readonly>
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        
                            <button class="btn btn-center btn-icon btn-icon-end btn-primary w-50" type="submit">
                                <span>Submit</span>
                                <i data-acorn-icon="chevron-right"></i>
                            </button>
                        </form>
                        
                        
        
                        <script>
                            document.querySelectorAll('.purpose').forEach(select => {
                                select.addEventListener('change', function() {
                                    const amountInput = this.closest('tr').querySelector('.amount');
                                    const totalInput = this.closest('tr').querySelector('.total');
                                    const copyInput = this.closest('tr').querySelector('.copy');
                                    const selectedOption = this.options[this.selectedIndex];
                                    const amountValue = selectedOption.dataset.amount;
                                    amountInput.value = amountValue;
                                    totalInput.value = amountValue * copyInput.value;
                                });
                            });
        
                            document.querySelectorAll('.copy').forEach(select => {
                                select.addEventListener('change', function() {
                                    const amountInput = this.closest('tr').querySelector('.amount');
                                    const totalInput = this.closest('tr').querySelector('.total');
                                    const amountValue = amountInput.value; // Keep it as string
                                    const copyValue = this.value;
                                    totalInput.value = amountValue * copyValue;
                                });
                            });
                        </script>
                    </div>
                </section>
                <!-- Content End -->
            </div>
      
        
    </div>



    <!-- Vendor Scripts Start -->
    <script src="/js/vendor/jquery-3.5.1.min.js"></script>
    <script src="/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="/js/vendor/OverlayScrollbars.min.js"></script>
    <script src="/js/vendor/autoComplete.min.js"></script>
    <script src="/js/vendor/clamp.min.js"></script>

    <script src="/icon/acorn-icons.js"></script>
    <script src="/icon/acorn-icons-interface.js"></script>

    <!-- Vendor Scripts End -->

    <!-- Template Base Scripts Start -->
    <script src="/js/base/helpers.js"></script>
    <script src="/js/base/globals.js"></script>
    <script src="/js/base/nav.js"></script>
    <script src="/js/base/search.js"></script>
    <script src="/js/base/settings.js"></script>
    <!-- Template Base Scripts End -->
    <!-- Page Specific Scripts Start -->

    <script src="/js/common.js"></script>
    <script src="/js/scripts.js"></script>
    <!-- Page Specific Scripts End -->
</body>

</html>
</x-app-layout>