<?php
$activePage = 'departamentos';
$pageTitle  = 'Actualizar departamento';
require_once __DIR__.'/../config/db.php';

$mensaje = ''; $clase = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $stmt = $conn->prepare("
      UPDATE departamento
      SET    nombre_dept = :nombre
      WHERE  iddept      = :id
    ");
    $stmt->execute(array(
      ':nombre' => $_POST['nombre_dept'],
      ':id'     => $_POST['iddept']
    ));
    $mensaje = 'Cambios guardados correctamente.';
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

<a class="btn btn-outline-primary" href="listar_departamentos.php">
  <i class="fa fa-arrow-left"></i> Volver al listado
</a>

<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';

