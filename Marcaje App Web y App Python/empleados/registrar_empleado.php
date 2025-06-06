<?php
$activePage = 'empleados';
$pageTitle  = 'Guardar empleado';

require_once __DIR__.'/../config/db.php';

$mensaje = ''; $clase = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $sql = "
      INSERT INTO empleado (idempleado, nombre_empleado, iddept, idjornada)
      VALUES (:id, :nombre, :dept, :jor)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array(
      ':id'     => $_POST['idempleado'],
      ':nombre' => $_POST['nombre_empleado'],
      ':dept'   => $_POST['iddept'],
      ':jor'    => $_POST['idjornada']
    ));
    $mensaje = 'Empleado registrado correctamente.';
    $clase   = 'success';
  } catch (PDOException $e) {
    $mensaje = 'Error: '.$e->getMessage();
    $clase   = 'danger';
  }
}

ob_start();
?>

<div class="alert alert-<?php echo $clase; ?> shadow-sm" role="alert">
  <?php echo htmlspecialchars($mensaje); ?>
</div>

<a class="btn btn-outline-primary" href="listar_empleados.php">
  <i class="fa fa-arrow-left"></i> Volver al listado
</a>

<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';

