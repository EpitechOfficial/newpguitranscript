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
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;700&display=swap" rel="stylesheet" />
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        .form-control {
            margin-bottom: 20px;
            }
            label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            }
            input[type="file"] {
            border: 1px solid #ccc;
            padding: 8px;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            }
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
        }

        #form {
            background: #fff;

            padding: 3rem;
            display: flex;
            flex-direction: column;
        }

        #form label {
            color: #0a2b4f;
            display: block;
            font-weight: bold;
        }

        .bold {
            margin-bottom: 1rem;
            font-weight: bold;
        }


        #form form .btn {
            background: #03182e !important;
            border: 1px solid #03182e !important;
            transition: 0.3s ease-in-out !important;
            width: 50% !important;
            text-align: center !important;
            padding-left: 0 !important;
        }

        #form form .btn:hover {
            background: #0a2b4f;
            border: 1px solid #0a2b4f !important;
            color: #fff;

        }

        .left {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #000;
            background: #e4f1ff;
            padding: 2rem;


        }

        .left .head {
            font-family: "Nunito Sans", sans-serif;
            font-size: 2rem;
            padding: 1rem;
            text-align: center;
            font-weight: 700;
            color: #000;

        }

        .right #form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

        }

        .right #form i {
            background: #03182e !important;
            color: #fff;
            font-size: 2rem;
            padding: 1rem;
            border-radius: 5rem;
            margin-bottom: 1rem;
        }

        .right #form .btn {
            width: 100% !important;
        }

        .box {
            margin: auto;
            max-width: 1050px;
            padding: 1rem;
            padding-bottom: 0 !important;
            overflow: auto;

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
            margin-bottom: 1rem;
        }

        .reg {
            color: #03182e;
            text-decoration: underline;
        }

        .noacc {
            font-size: 0.8rem;
        }

        @media (max-width:950px) {
            .lr {
                display: block;
            }

        }
    </style>
    <link rel="stylesheet" href="style.css">
    <title>Transcript Application | Sign In Page</title>
</head>

<body>
    <nav id="navchecker">
        <div class="mr">
            <img style="width:50px" src="img/ui-logo.png" class="logo">
        </div>

        <div>
            <h1 class="h3 m-0 font-weight-100 text-primary" style="">University of
                Ibadan,<br>The&nbsp;Postgraduate&nbsp;College</h1>

        </div>
        <div class="ml">
            <img style="width:50px" src="img/logo.png" class="logo">
        </div>





    </nav>
    <section>

        <div class="box lr">

            <div>
                @if (session()->has('success'))
                    <div>
                        {{ session('success') }}
                    </div>
                @endif
            </div>

            <div class="right">
                {{ $slot }}
            </div>
        </div>

    </section>




    <footer id="main-footer" class="bg-light" onmouseover="closeCity(event, 'Paris')">

        <p>Copyright &copy;<?php echo date('Y'); ?>, Postgraduate College, All Rights Reserved</p>
    </footer>


</body>

</html>
