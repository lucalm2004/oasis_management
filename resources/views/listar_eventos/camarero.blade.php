<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="your_csrf_token_here"> <!-- Agrega tu token CSRF aquí -->
    <title>Document</title>
</head>
<body>
    <table id="resultado">
    </table>

    <script>
        function ListarEventos() {
            var ajax = new XMLHttpRequest();
            var formdata = new FormData();

            // Agrega el token CSRF al FormData
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            formdata.append('_token', csrfToken);

            ajax.open('GET', 'camarero/eventos'); 

            ajax.onload = function () {
                if (ajax.status >= 200 && ajax.status < 300) {
                    var json = JSON.parse(ajax.responseText);
                    var listar_eventos = json.listar_eventos;
                    var tabla = "";

                    listar_eventos.forEach(function(item) {
                        var str = "<tr>";
                        str += "<td>" + item.id_discoteca + "</td>";
                        str += "<td>" + item.id_users + "</td>";
                        str += "</tr>";
                        tabla += str;
                    });
                    document.getElementById("resultado").innerHTML = tabla;
                } else {
                    document.getElementById("resultado").innerHTML = 'Error';
                }
            };
            ajax.send(formdata);
        }

        // Llama a la función para listar eventos al cargar la página
        window.onload = function() {
            ListarEventos();
        };
    </script>
</body>
</html>
