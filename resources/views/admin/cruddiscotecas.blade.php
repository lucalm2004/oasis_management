<!DOCTYPE html>
<html lang="en">


<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Oasis Management - Administrar Discotecas</title>
  <!-- Se ha de añadir el token para poder usarlo en el formdata de AJAX -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>


<body>

  <header>
    <a href="{{ route('admin.crudusuarios') }}">Crud Usuarios</a>
    <a href="{{ route('admin.cruddiscotecas') }}">Crud Discotecas</a>
  </header>
    <h1 style="text-align: center">DISCOTECAS</h1>
<div class="container" >
  <div class="row">
    <!-- Formulario de la izquierda (4 de las 12 columnas de bootstrap)
    Lo usaremos tanto para registrar un nuevo producto como para actualizar uno existente.
    Modificaremos su contenido y el botón por AJAX.
    -->
    <div class="col-lg-12" style="border:1px solid">
        

 <!-- Zona de la derecha usando 8 de las 12 columnas de Bootstrap -->
    <div class="col-lg-12" style="border:1px solid">
      <!-- Primero (zona superior) un DIV con el formulario de búsqueda -->
      <div class="row">
        <div class="col-lg-12 ml-auto" style="border:1px solid">
          <form action="" method="post" id="frmbusqueda">
            <div class="form-group">
              <label for="buscar">Buscar:</label>
              <input type="text" name="buscar" id="buscar" placeholder="Buscar..." class="form-control">
            </div>
            <label for="ciudad">Ciudad</label>
            <select name="ciudad" id="ciudad" class="button-40">
              <option value=""></option>
              
          </select>
          </form>
        </div>
        <br>
        <button class="btn-primary" id="CrearDiscoteca">Crear Discoteca</button>


        <!-- Segundo una tabla con los datos del CRUD a mostrar -->
        <div class="col-lg-12 ml-auto" style="border:1px solid">
          <table class="table table-hover table-responsive">
            <thead class="thead-dark">
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Imagen</th>
                  <th>Ciudad</th>
                  <th>Dirección</th>
                  <th>Editar</th>
                  <th>Eliminar</th>
                </tr>
            </thead>
            <tbody id="resultado">
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div> 
</div> 



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
   

<script src="{{ asset('js/adminDiscotecas.js') }}"></script>
   
</body>


</html>