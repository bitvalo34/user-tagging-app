<?php
$activePage = 'jornadas';
$pageTitle  = 'Registrar jornada';
ob_start();
?>

<h1 class="h3 fw-bold mb-4">Registrar jornada</h1>

<form action="crear_jornada.php" method="post"
      class="card shadow-sm p-4 mx-auto" style="max-width:500px;">

  <div class="form-floating mb-3">
    <input type="number" class="form-control" id="id"
           name="idjornada" required>
    <label for="id">ID de la jornada</label>
  </div>

  <div class="form-floating mb-3">
    <input type="text" class="form-control" id="nombre"
           name="nombre_jornada" required>
    <label for="nombre">Nombre</label>
  </div>

  <div class="form-floating mb-3">
    <input type="time" class="form-control" id="hEntrada"
           name="hora_entrada" required>
    <label for="hEntrada">Hora de entrada</label>
  </div>

  <div class="form-floating mb-4">
    <input type="time" class="form-control" id="hSalida"
           name="hora_salida" required>
    <label for="hSalida">Hora de salida</label>
  </div>

  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <button class="btn btn-primary" type="submit">
      <i class="fa fa-floppy-disk me-1"></i> Guardar
    </button>
    <a class="btn btn-link" href="listar_jornadas.php">Cancelar</a>
  </div>
</form>

<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';

