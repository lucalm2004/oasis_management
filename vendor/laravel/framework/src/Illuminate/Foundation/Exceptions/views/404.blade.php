<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego del Muñeco</title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        body {
            height: 100vh;
            background: #584040;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 920px;
            height: 280px;
            margin: 0 auto;
            position: relative;
            background: linear-gradient(#B7D6C7, #FFE2D1);
            overflow: hidden;
        }

        .muñeco {
            width: 56.75px;
            height: 84px;
            position: absolute;
            bottom: 22px;
            left: 42px;
            z-index: 2;
            background: url("img/gogogo.png") repeat-x 0px 0px;
            background-size: 291px 84px;
            background-position-x: 0px;
        }

        .muñeco-corriendo {
            animation: animarMuñeco 0.25s steps(2) infinite;
        }

        .muñeco-estrellado {
            background-position-x: -289px;
        }

        .suelo {
            width: 200%;
            height: 42px;
            position: absolute;
            bottom: 0;
            left: 0;
            background: url("img/suelo.png") repeat-x 0px 0px;
            background-size: 50% 42px;
        }

        .score {
            width: 100px;
            height: 30px;
            position: absolute;
            top: 5px;
            right: 15px;
            z-index: 10;
            color: #D48872;
            font-family: Verdana;
            font-size: 30px;
            font-weight: bold;
            text-align: right;
        }

        .cactus {
            width: 46px;
            height: 96px;
            position: absolute;
            bottom: 16px;
            z-index: 1;
            background: url("img/cactus1.png");
        }

        #gameOver {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 48px;
            color: red;
            font-family: Verdana;
        }

        #resetButton {
            display: none;
            position: absolute;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 10px 20px;
            font-size: 20px;
            background-color: #D48872;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        @keyframes animarMuñeco {
            from {
                background-position-x: 131px;
            }
            to {
                background-position-x: -289px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="suelo"></div>
        <div class="muñeco muñeco-corriendo"></div>
        <div class="score">0</div>
        <div id="gameOver">GAME OVER</div>
        <button id="resetButton" onclick="ResetGame()">Restart</button>
    </div>

    <script>
        var tiempo = new Date();
        var delta_tiempo = 0;
        var sueloX = 0;
        var velEscenario = 1200 / 3;
        var gameVel = 1;
        var sueloY = 22;
        var velY = 0;
        var impulso = 900;
        var gravedad = 2500;
        var muñecoPosX = 42;
        var muñecoPosY = sueloY;
        var container, suelo, muñeco, textoScore, gameOver, resetButton;
        var score = 0;
        var saltando = false;
        var parado = false;
        var tiempoHastaObstaculo = 2;
        var tiempoObstaculoMin = 0.7;
        var tiempoObstaculoMax = 1.8;
        var obstaculos = [];

        if (document.readyState === "complete" || document.readyState === "interactive") {
            setTimeout(Init, 1);
        } else {
            document.addEventListener("DOMContentLoaded", Init);
        }

        function Init() {
            tiempo = new Date();
            Start();
            Loop();
        }

        function Start() {
            suelo = document.querySelector(".suelo");
            container = document.querySelector(".container");
            textoScore = document.querySelector(".score");
            muñeco = document.querySelector(".muñeco");
            gameOver = document.querySelector("#gameOver");
            resetButton = document.querySelector("#resetButton");
            document.addEventListener("keydown", HandleKeyDown);
        }

        function HandleKeyDown(event) {
            if (event.keyCode === 32) {
                Saltar();
            }
        }

        function Saltar() {
            if (muñecoPosY === sueloY) {
                saltando = true;
                velY = impulso;
                muñeco.classList.remove("muñeco-corriendo");
            }
        }

        function Loop() {
            var ahora = new Date();
            delta_tiempo = (ahora - tiempo) / 1000;
            tiempo = ahora;
            Update();
            requestAnimationFrame(Loop);
        }

        function Update() {
            if (parado) return;

            MoverSuelo();
            MoverMuñeco();
            DecidirCrearObstaculos();
            MoverObstaculos();
            DetectarColision();
            velY -= gravedad * delta_tiempo;
        }

        function MoverSuelo() {
            sueloX += CalcularDesplazamiento();
            suelo.style.left = -(sueloX % container.clientWidth) + "px";
        }

        function CalcularDesplazamiento() {
            return velEscenario * delta_tiempo * gameVel;
        }

        function MoverMuñeco() {
            muñecoPosY += velY * delta_tiempo;
            if (muñecoPosY < sueloY) {
                TocarSuelo();
            }
            muñeco.style.bottom = muñecoPosY + "px";
        }

        function TocarSuelo() {
            muñecoPosY = sueloY;
            velY = 0;
            if (saltando) {
                muñeco.classList.add("muñeco-corriendo");
            }
            saltando = false;
        }

        function DecidirCrearObstaculos() {
            tiempoHastaObstaculo -= delta_tiempo;
            if (tiempoHastaObstaculo <= 0) {
                CrearObstaculo();
            }
        }

        function CrearObstaculo() {
            var obstaculo = document.createElement("div");
            container.appendChild(obstaculo);
            obstaculo.classList.add("cactus");
            obstaculo.posX = container.clientWidth;
            obstaculo.style.left = container.clientWidth + "px";
            obstaculos.push(obstaculo);
            tiempoHastaObstaculo = tiempoObstaculoMin + Math.random() * (tiempoObstaculoMax - tiempoObstaculoMin) / gameVel;
        }

        function MoverObstaculos() {
            for (var i = obstaculos.length - 1; i >= 0; i--) {
                if (obstaculos[i].posX < -obstaculos[i].clientWidth) {
                    obstaculos[i].parentNode.removeChild(obstaculos[i]);
                    obstaculos.splice(i, 1);
                    GanarPuntos();
                } else {
                    obstaculos[i].posX -= CalcularDesplazamiento();
                    obstaculos[i].style.left = obstaculos[i].posX + "px";
                }
            }
        }

        function GanarPuntos() {
            score++;
            textoScore.innerText = score;
        }

        function DetectarColision() {
            for (var i = 0; i < obstaculos.length; i++) {
                if (obstaculos[i].posX > muñecoPosX + muñeco.clientWidth) {
                    break;
                } else {
                    if (IsCollision(muñeco, obstaculos[i])) {
                        GameOver();
                        break;
                    }
                }
            }
        }

        function IsCollision(a, b) {
            var aRect = a.getBoundingClientRect();
            var bRect = b.getBoundingClientRect();

            return !(
                aRect.top > bRect.bottom ||
                aRect.bottom < bRect.top ||
                aRect.left > bRect.right ||
                aRect.right < bRect.left
            );
        }

        function GameOver() {
            Estrellarse();
            gameOver.style.display = "block";
            resetButton.style.display = "block";
        }

        function Estrellarse() {
            // muñeco.classList.remove("muñeco-corriendo");
            // muñeco.classList.add("muñeco-estrellado");
            parado = true;
        }

        function ResetGame() {
            gameOver.style.display = "none";
            resetButton.style.display = "none";
            // muñeco.classList.remove("muñeco-estrellado");
            muñeco.classList.add("muñeco-corriendo");
            parado = false;
            score = 0;
            textoScore.innerText = score;
            obstaculos.forEach(function(obstaculo) {
                obstaculo.parentNode.removeChild(obstaculo);
            });
            obstaculos = [];
            muñecoPosY = sueloY;
            velY = 0;
            tiempoHastaObstaculo = 2;
        }
    </script>
</body>
</html>
