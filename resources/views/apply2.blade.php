<x-app-layout :pageName="'Apply'">
    <?php
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="img/favicon/favicon-196x196.png" sizes="196x196" />
        <link rel="icon" type="image/png" href="img/favicon/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/png" href="img/favicon/favicon-32x32.png" sizes="32x32" />
        <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png" sizes="16x16" />
        <link rel="icon" type="image/png" href="img/favicon/favicon-128.png" sizes="128x128" />
        <script src="https://kit.fontawesome.com/ba46c9c7c0.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="bootstrap.min.css">
        <link rel="icon" href="logo.png" type="image/x-icon" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;700&display=swap"
            rel="stylesheet" />
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .checker {
                color: #0a2b4f;

                margin-top: 2rem;
                font-size: 1.5rem;
                text-align: center;
                font-weight: bold;
            }

            #feechecker {
                background: #0a2b4f;
                border-radius: 50px;
                padding: 3rem;
                display: flex;
                flex-direction: column;
            }

            #feechecker label {
                color: #fff;
            }

            #feechecker form .btn {
                border: 2px solid #FFCA42 !important;
            }


            #myTable th {
                text-align: left !important;
                background: #0a2b4f;
                color: #fff;

            }

            #myTable td {
                text-align: left !important;
            }

            .table th:first-child {
                border-top-left-radius: 10px;

            }

            .table th:last-child {
                border-top-right-radius: 10px;

            }

            .table tr:hover {
                background-color: #01314852;
                border: 2px solid #f1f1f1 !important;
                color: #000;
            }

            .table a {
                color: #000;

            }

            #navchecker {
                background: #fff;
                display: flex;
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
                justify-content: center;
                align-items: center;
                text-align: center;
            }



            .left {
                background: #0a2b4f;

            }

            .lr {
                display: flex;
                flex-direction: column;
                /* box-shadow: 0 0 30px 0 #095484; */
            }

            #formr {
                background: #fff;

                padding: 3rem;
                display: flex;
                flex-direction: column;
            }

            #formr label {
                color: #0a2b4f;
                display: block;

            }

            .bold {
                margin-bottom: 1rem;
                font-weight: bold;
            }


            #formr form .btn {
                background: #03182e !important;
                border: 1px solid #03182e !important;
                transition: 0.3s ease-in-out !important;
                width: 100% !important;
                text-align: center !important;
                padding-left: 0 !important;
            }

            #formr form .btn:hover {
                background: #0a2b4f;
                border: 1px solid #0a2b4f !important;
                color: #fff;

            }

            .left {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: flex-start;
                color: #000;
                background: #e4f1ff;
                padding-left: 3rem;
                padding-right: 3rem;



            }

            .left .head {
                font-family: "Nunito Sans", sans-serif;
                font-size: 1.5rem;
                padding: 1rem;
                padding-left: 0 !important;
                padding-bottom: 0 !important;
                text-align: center;
                font-weight: 700;
                color: #000;

            }

            .right #formr {
                display: flex;
                flex-direction: column;


            }

            .right #formr i {
                background: #03182e !important;
                color: #fff;
                font-size: 2rem;
                padding: 1rem;
                border-radius: 5rem;
                margin-bottom: 1rem;
            }

            .box {
                margin: auto;
                max-width: 750px;
                box-shadow: 0 0 30px 0 #03182e !important;
                padding-bottom: 0 !important;
                overflow: auto;
                margin-top: 2rem;

            }

            .box input {
                padding-left: 2rem !important;
                margin-bottom: 0.5rem;
                border-radius: 1rem !important;
                outline: none;
                border: 1px solid #0a2b4f;



            }

            .mr {
                margin-right: 1rem;
            }

            .ml {
                margin-left: 1rem;
            }

            li {
                list-style: square !important;

            }

            .reg {
                color: #03182e;
                text-decoration: underline;
            }

            .noacc {
                font-size: 1rem;
                margin-top: 1rem;
            }

            #formr form .twoform {
                display: flex;


            }

            #formr form .twoform input {
                width: 310px !important;

            }

            @media (max-width:950px) {
                #formr form .twoform {
                    display: block;
                }

                #formr form .twoform .mr {
                    margin: 0 !important;
                }

                #formr form .twoform input {
                    width: 100% !important;

                }

            }

            @media (max-width:550px) {

                #navchecker .h3 {
                    font-size: 1.3rem;
                }
            }

            #formr form input {
                width: 100%;
                padding: 0.7rem 0.5rem;
                margin-bottom: 1rem;
                border-radius: 8px !important;
                outline: none;
                border: 1px solid #0a2b4f;
            }

            #formr form .btn {
                width: 100%;
                font-size: 1rem;
                border: 1px solid #0a2b4f;


            }
        </style>
        <link rel="stylesheet" href="style.css">
        <title>Transcript Application | Apply</title>
    </head>






    </nav>
    <section>

        <div class="box lr">


            <div class="modal-body">
                <div class="card mb-2 w-100">
                    <div class="card-body h-100 w-100">
                        <h1 class="mb-0 pb-0 display-4" id="title">Transcript Request</h1>
                        <nav class="breadcrumb-container w-100 d-inline-block" aria-label="breadcrumb">
                            <form method="post" action="{{ route('dashboard.store') }}" enctype="multipart/form-data">
                                @csrf
                                <h2 class="mt-4">Please provide details of your Transcript request and add to cart
                                </h2>

                                <div class="mt-10 col-md-12">
                                    <div class="row ">
                                        <div class="col form-group">
                                            <label for="transcript_type">Transcript Type</label>
                                            <select id="transcript_type" class="form-control" name="transcript_type"
                                                required>
                                                <option value="">Select Transcript Type</option>
                                                @foreach ($requestTypes as $type)
                                                    <option value="{{ $type->requesttype }}">{{ $type->requesttype }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col form-group">
                                            <label for="number_of_copies">Number of Copies</label>
                                            <select id="number_of_copies" name="number_of_copies" class="form-control"
                                                required>
                                                <option value="">Select Number of Copies</option>
                                                <option value="1">One (1)</option>
                                                <option value="2">Two (2)</option>
                                                <option value="3">Three (3)</option>
                                                <option value="4">Four (4)</option>
                                                <option value="5">Five (5)</option>
                                            </select>
                                        </div>

                                    </div>


                                    <div class="mt-4">
                                        <div class="col form-group">
                                            <label for="file"><strong>Please Upload Notification of Result or
                                                    Certificate</strong></label>
                                            <input type="file" id="file" name="file" required
                                                class="form-control">

                                        </div>

                                    </div>

                                    <div class="mt-0 col d-flex justify-content-center">
                                        <button class="btn btn-primary mt-4 w-full w-50" type="submit"
                                            id="addToCart">Add to
                                            Cart</button>
                                    </div>
                            </form>
                    </div>

                    </nav>
                </div>
            </div>
        </div>

    </section>




    <footer id="main-footer" class="bg-light" onmouseover="closeCity(event, 'Paris')">

        <p>Copyright &copy;<?php echo date('Y'); ?>, Postgraduate College, All Rights Reserved</p>
    </footer>


    </body>

    </html>
</x-app-layout>
