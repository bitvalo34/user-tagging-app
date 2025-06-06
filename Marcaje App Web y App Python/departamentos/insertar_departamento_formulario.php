<?php
$activePage = 'departamentos';
$pageTitle  = 'Registrar departamento';
ob_start();
?>

<h1 class="h3 fw-bold mb-4">Registrar departamento</h1>

<form action="insertar_departamento.php" method="post"
      class="card shadow-sm p-4 mx-auto" style="max-width:500px;">
  <div class="form-floating mb-3">
    <input type="number" class="form-control" id="iddept"
           name="iddept" required>
    <label for="iddept">ID del departamento</label>
  </div>

  <div class="form-floating mb-3">
    <input type="text" class="form-control" id="nombre"
           name="nombre_dept" required>
    <label for="nombre">Nombre del departamento</label>
  </div>

  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <button class="btn btn-primary" type="submit">
      <i class="fa fa-floppy-disk me-1"></i> Guardar
    </button>
    <a class="btn btn-link" href="listar_departamentos.php">Cancelar</a>
  </div>
</form>

<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';

