<?php
$activePage = 'permisos';
$pageTitle  = 'Registrar permiso';
require_once __DIR__.'/../config/db.php';

/* Empleados para el <select> */
$empleados = $conn->query("
  SELECT idempleado, nombre_empleado
  FROM   empleado
  ORDER  BY nombre_empleado
")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h1 class="h3 fw-bold mb-4">Registrar permiso</h1>

<form action="registrar_permiso.php" method="post"
      class="card shadow-sm p-4 mx-auto" style="max-width:600px;">

  <div class="form-floating mb-3">
    <input type="number" class="form-control" id="idpermiso"
           name="idpermiso" required>
    <label for="idpermiso">ID del permiso</label>
  </div>

  <div class="form-floating mb-3">
    <select class="form-select" id="empleado" name="idempleado" required>
      <option selected disabled value="">Seleccione…</option>
      <?php foreach ($empleados as $e): ?>
        <option value="<?php echo $e['idempleado']; ?>">
          <?php echo htmlspecialchars($e['nombre_empleado']); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <label for="empleado">Empleado</label>
  </div>

  <div class="form-floating mb-3">
    <input type="date" class="form-control" id="fecha"
           name="fecha_permiso" required>
    <label for="fecha">Fecha</label>
  </div>

  <div class="form-floating mb-4">
    <select class="form-select" id="motivo" name="motivo_falta" required>
      <option value="ausencia">Ausencia</option>
      <option value="tardanza">Tardanza</option>
      <option value="salida_temprana">Salida temprana</option>
    </select>
    <label for="motivo">Motivo</label>
  </div>

  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <button class="btn btn-primary" type="submit">
      <i class="fa fa-floppy-disk me-1"></i> Guardar
    </button>
    <a class="btn btn-link" href="listar_permisos.php">Cancelar</a>
  </div>
</form>

<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';

