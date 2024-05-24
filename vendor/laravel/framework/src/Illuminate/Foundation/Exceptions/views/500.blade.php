<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <title>Oasis Managament - Error 500</title>
    <style>
        :root {
            --color-black: #161616;
            --color-white: #fff;
            --size: 170px;
        }

        /* General page styling */
        html, body {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--color-black);
            margin: 0;
            padding: 0;
            flex-direction: column;
        }

        h1 {
            color: #fff;
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 20px 0;
            text-align: center;
            margin-bottom: 20%;
        }

        .cat {
            position: relative;
            height: var(--size);
            width: calc(var(--size) * 1.13);
        }

        /* Ears */
        .ear {
            position: absolute;
            top: -30%;
            height: 60%;
            width: 25%;
            background: var(--color-white);
        }

        .ear::before,
        .ear::after {
            content: '';
            position: absolute;
            bottom: 24%;
            height: 10%;
            width: 5%;
            border-radius: 50%;
            background: var(--color-black);
        }

        .ear--left {
            left: -7%;
            border-radius: 70% 30% 0 0 / 100% 100% 0 0;
            transform: rotate(-15deg);
        }

        .ear--left::before,
        .ear--left::after {
            right: 10%;
        }

        .ear--left::after {
            transform: rotate(-45deg);
        }

        .ear--right {
            right: -7%;
            border-radius: 30% 70% 0 0 / 100% 100% 0 0;
            transform: rotate(15deg);
        }

        .ear--right::before,
        .ear--right::after {
            left: 10%;
        }

        .ear--right::after {
            transform: rotate(45deg);
        }

        /* Face */
        .face {
            position: absolute;
            height: 100%;
            width: 100%;
            background: var(--color-black);
            border-radius: 50%;
        }

        /* Eyes */
        .eye {
            position: absolute;
            top: 35%;
            height: 30%;
            width: 31%;
            background: var(--color-white);
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
        }

        .eye::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 0;
            width: 100%;
            border-radius: 0 0 50% 50% / 0 0 40% 40%;
            background: var(--color-black);
            animation: blink 4s infinite ease-in;
        }

        @keyframes blink {
            0% { height: 0; }
            90% { height: 0; }
            92.5% { height: 100%; }
            95% { height: 0; }
            97.5% { height: 100%; }
            100% { height: 0; }
        }

        .eye::before {
            content: '';
            position: absolute;
            top: 60%;
            height: 10%;
            width: 15%;
            background: var(--color-white);
            border-radius: 50%;
        }

        .eye--left {
            left: 0;
        }

        .eye--left::before {
            right: -5%;
        }

        .eye--right {
            right: 0;
        }

        .eye--right::before {
            left: -5%;
        }

        /* Pupils */
        .eye-pupil {
            position: absolute;
            top: 25%;
            height: 50%;
            width: 20%;
            background: var(--color-black);
            border-radius: 50%;
            animation: look-around 4s infinite;
        }

        @keyframes look-around {
            0% { transform: translate(0); }
            5% { transform: translate(50%, -25%); }
            10% { transform: translate(50%, -25%); }
            15% { transform: translate(-100%, -25%); }
            20% { transform: translate(-100%, -25%); }
            25% { transform: translate(0, 0); }
            100% { transform: translate(0, 0); }
        }

        .eye--left .eye-pupil {
            right: 30%;
        }

        .eye--right .eye-pupil {
            left: 30%;
        }

        .eye-pupil::after {
            content: '';
            position: absolute;
            top: 30%;
            right: -5%;
            height: 20%;
            width: 35%;
            border-radius: 50%;
            background: var(--color-white);
        }

        /* Muzzle */
        .muzzle {
            position: absolute;
            top: 60%;
            left: 50%;
            height: 6%;
            width: 10%;
            background: var(--color-white);
            transform: translateX(-50%);
            border-radius: 50% 50% 50% 50% / 30% 30% 70% 70%;
        }
    </style>
</head>
<body>
    <h1>ERROR 500... ALIMENTA A ESTE MAPACHE </h1>
    <div class="cat">
        <div class="ear ear--left"></div>
        <div class="ear ear--right"></div>
        <div class="face">
            <div class="eye eye--left">
                <div class="eye-pupil"></div>
            </div>
            <div class="eye eye--right">
                <div class="eye-pupil"></div>
            </div>
            <div class="muzzle"></div>
        </div>
    </div>
</body>
</html>
