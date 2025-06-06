<?php
$activePage = 'jornadas';
$pageTitle  = 'Guardar jornada';
require_once __DIR__.'/../config/db.php';

$mensaje = ''; $clase = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $sql = "
      INSERT INTO jornada (idjornada, nombre_jornada, hora_entrada, hora_salida)
      VALUES (:id, :nombre, :entrada, :salida)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array(
      ':id'      => $_POST['idjornada'],
      ':nombre'  => $_POST['nombre_jornada'],
      ':entrada' => $_POST['hora_entrada'],
      ':salida'  => $_POST['hora_salida']
    ));
    $mensaje = 'Jornada registrada correctamente.';
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

<a class="btn btn-outline-primary" href="listar_jornadas.php">
  <i class="fa fa-arrow-left"></i> Volver al listado
</a>

<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';

