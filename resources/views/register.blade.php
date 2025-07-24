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
    #formr form .twoform input{
      width: 310px !important;

    }
    @media (max-width:950px){
      #formr form .twoform{
        display: block;
    }
      #formr form .twoform .mr{
        margin: 0 !important;
    }
    #formr form .twoform input{
      width: 100% !important;

    }

}
    @media (max-width:550px){

    #navchecker .h3{
      font-size: 1.3rem;
    }
}
    #formr form input{
  width: 100%;
  padding: 0.7rem 0.5rem;
  margin-bottom: 1rem;
  border-radius: 8px !important;
  outline: none;
  border: 1px solid #0a2b4f  ;
}

#formr form .btn{
  width: 100%;
  font-size: 1rem;
  border: 1px solid #0a2b4f;


}
  </style>
  <link rel="stylesheet" href="style.css">
  <title>Transcript Application | Sign Up Page</title>
</head>

<body>
  <nav id="navchecker">
    <div class="mr">
      <img style="width:50px" src="img/ui-logo.png" class="logo">
    </div>

    <div>
      <h1 class="h3 m-0 font-weight-100 text-primary" style="">University of Ibadan,<br>The&nbsp;Postgraduate&nbsp;College</h1>

    </div>
    <div class="ml">
      <img style="width:50px" src="img/logo.png" class="logo">
    </div>





  </nav>
  <section>

    <div class="box lr">

      <div class="left">
        <p class="noacc">
          If you have an account already, click
          <a class="reg" href="/">Sign In.</a>
        </p>
        {{-- <div class="">
          <p class="head">Instruction:</p>
        </div> --}}
        {{-- <ul class="mb-1">
          <li>

            Institutional (not personal/ individual) email address is to be submitted for official transcripts.

          </li>
          <li>

            Institutional (not personal/ individual) email address is to be submitted for official transcripts.

          </li>
          <li>

            Institutional (not personal/ individual) email address is to be submitted for official transcripts.

          </li>

        </ul> --}}



      </div>

     <!-- resources/views/authenticate/create.blade.php -->

<div class="right">
    <div id="formr">
        @if(session('success'))
            <div style="color: green; margin-bottom: 10px;">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div style="color: red; margin-bottom: 10px;">
                <ul>
                    @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('authenticate.store') }}">
            @csrf

            <div class="form-group">
                <label for="matric">Matriculation Number:</label>
                <input type="number" placeholder="Matriculation Number" name="matric" value="{{ old('matric') }}">
            </div>

            <div class="twoform">
                <div class="form-group mr">
                    <label for="Surname">Surname:</label>
                    <input type="text" placeholder="Surname" name="Surname" id="Surname" value="{{ old('Surname') }}">
                </div>
                <div class="form-group">
                    <label for="othername">Other Names:</label>
                    <input type="text" placeholder="Other Names" name="othername" id="othername" value="{{ old('othername') }}">
                </div>
            </div>

            <div class="twoform">
                <div class="form-group mr">
                    <label for="phone">Phone Number:</label>
                    <input type="number" placeholder="Phone Number" name="phone" id="phone" value="{{ old('phone') }}">
                </div>
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" placeholder="Email Address" name="email" id="email" value="{{ old('email') }}">
                </div>
            </div>

            <div class="twoform">
                <div class="form-group mr">
                    <label for="password">Password:</label>
                    <input type="password" placeholder="Password" name="password" id="password">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password:</label>
                    <input type="password" placeholder="Confirm Password" name="password_confirmation" id="password_confirmation">
                </div>
            </div>

            <input type='submit' value='Sign Up' class='btn'>
        </form>
    </div>
</div>


  </section>




  <footer id="main-footer" class="bg-light" onmouseover="closeCity(event, 'Paris')">

    <p>Copyright &copy;<?php echo date("Y"); ?>, Postgraduate College, All Rights Reserved</p>
  </footer>


</body>

</html>
