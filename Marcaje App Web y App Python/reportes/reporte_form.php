<?php
$activePage = 'reportes';
$pageTitle  = 'Generar reporte';
require_once __DIR__.'/../config/db.php';

$empleados = $conn->query("
  SELECT idempleado, nombre_empleado
  FROM   empleado
  ORDER  BY nombre_empleado
")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>
<h1 class="h3 fw-bold mb-4">Reporte de Entradas y Salidas</h1>

<form action="reporte_vista.php" method="get" class="card shadow-sm p-4" style="max-width:550px;">
  <div class="form-floating mb-3">
    <select class="form-select" id="emp" name="idempleado" required>
      <option selected disabled value="">Seleccione…</option>
      <?php foreach ($empleados as $e): ?>
        <option value="<?php echo $e['idempleado']; ?>">
          <?php echo htmlspecialchars($e['nombre_empleado']); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <label for="emp">Empleado</label>
  </div>

  <div class="row g-3 mb-4">
    <div class="col">
      <label class="form-label">Desde</label>
      <input type="date" class="form-control" name="desde" required>
    </div>
    <div class="col">
      <label class="form-label">Hasta</label>
      <input type="date" class="form-control" name="hasta" required>
    </div>
  </div>

  <button type="submit" class="btn btn-primary">
    <i class="fa fa-eye me-1"></i> Ver reporte
  </button>
</form>
<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';
