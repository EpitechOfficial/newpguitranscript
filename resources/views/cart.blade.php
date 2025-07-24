<x-app-layout :pageName="'Cart'">
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title">
            <div class="row w-full w-100">
                <!-- Title Start -->
                <div class="">
                    {{-- <h1 class="mb-0 pb-0 display-4" id="title">Dashboard</h1> --}}
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    </nav>
                </div>
                <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->
        <!-- Content Start -->
        <div class="card mb-2">
            {{-- <div class="card-body h-100">
                Welcome <h1> {{ session('user')->id }} {{ session('user')->Surname }} {{ session('user')->Other_names }}
                </h1>
            </div> --}}
        </div>
        <!-- Content End -->
        <div class="card mb-2 w-100">
            <div class="card-body h-100 w-100">
                <h1 class="mb-0 pb-0 display-4" id="title">Cart Details</h1>
                <br>
                <nav class="breadcrumb-container w-100 d-inline-block" aria-label="breadcrumb">
                    <div class="table-responsive">
                        <table class="table table-bordered">

                            @if (count($cartItems) > 0)

                                <table class="table">

                                    <thead>

                                        <tr>
                                            <th>S/N</th>
                                            <th>Transcript Type</th>
                                            <th>Number of Copies</th>
                                            <th>Cost per Copy</th>
                                            <th>Total Cost</th>
                                        </tr>

                                    </thead>

                                    <tbody>

                                        @foreach ($cartItems as $key => $cartItem)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    @php
                                                        $transcriptType = \App\Models\RequestType::where(
                                                            'type_id',
                                                            $cartItem['request'],
                                                        )->first();
                                                        echo $transcriptType ? $transcriptType->requesttype : '';
                                                    @endphp
                                                </td>

                                                <td>{{ $cartItem['num_copies'] ?? '' }}</td>

                                                @php

                                                    $transcriptType = \App\Models\RequestType::where(
                                                        'type_id',
                                                        $cartItem['request'],
                                                    )->first();

                                                    $costPerCopy = $transcriptType ? $transcriptType->amount : 0;

                                                    $totalCost = $costPerCopy * ($cartItem['num_copies'] ?? 0);

                                                @endphp

                                                <td>{{ $costPerCopy }}</td>

                                                <td>{{ $totalCost }}</td>


                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>

                                <a href="{{ route('dashboard') }}" class="btn btn-primary">Add More Items</a>

                                <a href="#" class="btn btn-success">Proceed to Checkout</a>
                            @else
                                <p>Your shopping cart is empty.</p>

                            @endif
                            </tbody>
                    </div>
            </div>
            </nav>
        </div>
        </table>
    </div>
    </div>

</x-app-layout>
