<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Laralink">
    <title>Transcript Application | Letter</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body,
        html {
            background: #fff !important;
        }
        .header .title {
            text-align: center;
            margin-bottom: 2rem;
            color: #0a2b4f;
        }
        .header .title h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 900;
        }
        .header .title h2 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }
        .header .title p {
            margin: 0;
            font-size: 1rem;
            font-style: italic;
        }
        .address {
            display: flex;
            align-items: center;
            gap: 20px;
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
            font-size: 1rem;
            font-style: italic;
        }
        .afterHead {
            padding-top: 2rem !important;
        }
        .bt {
            border-top: 2px solid black;
        }
        .underline{
            text-decoration: underline;
        }
        .italic {
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="letter">
        <div class="header">
            <div class="title">
                <h1>UNIVERSITY OF IBADAN, IBADAN, NIGERIA</h1>
                <h2>POSTGRADUATE COLLEGE</h2>
                <p>https://pgcollege.ui.edu.ng/</p>
            </div>
            <div class="address">
                <div class="left">
                    <p>PROVOST:</p>
                    <p>Prof. A.S.O. OGUNJUYIGBE,
                        <span>D. Tech (Pretoria), R. Eng, fspsp</span>
                    </p>
                    <p>Mobile: <span>+234 8023504826</span></p>
                    <p>Email: <span>aogunjuyigbe@yahoo.com, a.aogunjuyigbe@ui.edu.ng</span></p>
                </div>
                <div class="center">
                    <img src="{{ asset('img/ui-logo.png') }}" alt="">
                    <p>...Centre of excellence for postgraduate training and research</p>
                </div>
                <div class="right">
                    <p>DEPUTY REGISTRAR (Examination & Records)</p>
                    <p>MR. O.A. OLAOYE, B.A. (Ife), MMP (Ibadan), MANUPA, MCIPDM</p>
                    <p>Mobile: <span>+234 8055265713</span></p>
                    <p>Email: yemisiolaye6465@gmail.com</p>
                </div>
            </div>
            <div class="afterHead">
                <p class="bt text-center"><strong>Contact us: <span class="underline">records@pgcollege.ui.edu.ng</span></strong> </p>
                <div class="info-container">
                    <p>13 June, 2024</p>
                    <p>Visa Compliance Associate, Human Resources, Kings College, London, United Kingdom.</p>
                    <p>Academic Transcript: {{ $biodata->othername && $biodata->surname ? $biodata->othername . ' ' . $biodata->surname : $biodata->name }}</p>
                    <p>Matric No: {{ $biodata->matric }}</p>
                    <p>Please find attached the official transcript/academic records of the above-named candidate.</p>
                    <p>Please note that the transcript(s) is/are sent to you in confidence and should under no circumstances be made available to him/her for personal usage.</p>
                    <p>Yours faithfully,</p>
                    <p class="italic">
                        O. A. Olaoye <br>
                        Deputy Registrar <br>
                        (Examinations and Records)
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 