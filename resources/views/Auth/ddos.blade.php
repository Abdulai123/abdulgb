@php
    $waitTime = mt_rand(15, 30); // Wait time in seconds
    session(['await_time' => $waitTime]);

    if (!session()->has('start_time') || time() - session('start_time') > $waitTime) {
        session(['start_time' => time()]);

    } else {
        $waitTime = session('start_time') - time() + $waitTime;
    }

    if (time() < session('start_time') + $waitTime) {
        $waitTime = session('start_time') + $waitTime - time();
        session(['ddos_visited' => true]);
    } else {
        // Uncomment the following two lines if you want to set 'ddos_visited' to true and exit
        //session(['ddos_visited' => '15']);
        session(['ddos_visited' => true]);
        // exit();
    }
@endphp


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Whales Market | DDOS DEFENDER</title>
    <meta http-equiv="refresh" content="{{ $waitTime }};">

    <style>
        html {
            background-color: rgb(43, 43, 64);
            /*  rgb(43, 43, 64) rgba(0, 0, 0, 0) rgb(239, 242, 245)*/
        }

        .container {
            top: 10%;
            position: relative;
        }

        .whale-container {
            position: relative;
            width: 200px;
            height: 200px;
            overflow: hidden;
            background: linear-gradient(to bottom, #0080ff, #66ccff);
            box-shadow: 0px 0px 10px rgba(81, 191, 250, 0.7);
            border-radius: 100%;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            word-spacing: 7px;
            font-size: 40px;
        }

        .w {
            text-align: center;
            color: #0080ff;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-weight: bolder;
            letter-spacing: 5;
        }

        .m {
            text-align: center;
            color: #0b3996;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-weight: bolder;
            letter-spacing: 3;
        }

        .ddos {
            color: #66ccff;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-weight: bolder;
            letter-spacing: 2;
            font-size: 32px;
        }

        p {
            text-align: center;
            color: darkgray;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-weight: 700;
            line-height: 2;
        }

        .time {
            background-color: #0080ff;
            font-size: 18px;
            padding: 5px;
            color: white;
        }

        .dont {
            color: #ff4d4d;
            font-weight: bold;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 20px;
        }

        .freedom {
            margin-top: 5%;
            color: #c1c1c1;
            font-size: 20px;
            /* text-decoration: underline dashed green; */
        }

        .whale-container::before,
        .whale-container::after {
            content: "";
            position: absolute;
            bottom: -20px;
            left: 0;
            width: 100%;
            height: 70%;
            background: linear-gradient(to bottom, #0080ff 0%, #66ccff 100%);
            animation: wave 2s infinite linear;
        }

        .whale-container::after {
            bottom: -10px;
            opacity: 0.6;
            animation-delay: -1s;
        }

        .whale {
            position: absolute;
            top: 30%;
            /* Position the whale vertically in the middle of the container */
            left: 50%;
            /* Position the whale horizontally in the middle of the container */
            transform: translate(-50%, -50%);
            /* Center the whale within the container */
            width: 50%;
            /* Set the width of the image to match the size of the container */
            height: 50%;
            /* Set the height of the image to match the size of the container */
            object-fit: contain;
            /* Scale the image to fit within the container without distorting its aspect ratio */
            animation: swim 7s infinite linear;
            /* Use a longer animation time and a linear timing function */
            transform-origin: bottom right;
            /* Set the origin for the transform */
            filter: drop-shadow(0 0 10px #444);
            /* Add a drop shadow effect to the whale */
        }

        @keyframes swim {
            0% {
                transform: translateX(-100%) rotate(-10deg);
            }

            25% {
                transform: translateX(-50%) rotate(10deg);
            }

            50% {
                transform: translateX(0) rotate(-10deg);
            }

            75% {
                transform: translateX(50%) rotate(10deg);
            }

            100% {
                transform: translateX(100%) rotate(-10deg);
            }
        }

        @keyframes wave {
            0% {
                transform: translateX(0) scaleY(1);
            }

            50% {
                transform: translateX(-1%) scaleY(0.8);
            }

            100% {
                transform: translateX(0) scaleY(1);
            }
        }

        /* Add more variation to the waves */
        .whale-container::before {
            animation-duration: 4s;
            animation-timing-function: ease-in-out;
            animation-delay: 1s;
        }

        .whale-container::after {
            animation-duration: 3s;
            animation-timing-function: ease-in-out;
            animation-delay: 0.5s;
        }

        @media only screen and (max-width: 480px) {
            .whale {
                width: 40%;
                height: 40%;
                top: 50%;
                transform: translate(-50%, -50%) scale(0.8);
            }

            h1 {
                font-size: 30px;
            }

            p {
                font-size: 16px;
                line-height: 1.5;
            }

            .time {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><span class="w">WHALES</span> <span class="m">MARKET</span> <br> <span class="ddos">DDOS
                DEFENDER</span></h1>
        <div class="whale-container">
            <img src="data:image/png;base64,{{ $icon['whale'] }}" class="whale">
        </div>
        <p>Please wait for the estimated time of <span class="time">&lt;3 Minutes</span> while the whale leads the
            way.
            <br><span class="dont">Do not refresh the page,</span> You will be redirected automatically.
        </p>
        <p class="freedom">-Going deep down the horizon to meet the big whales_</p>
    </div>

</body>

</html>
