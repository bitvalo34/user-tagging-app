<?php
$activePage = 'jornadas';
$pageTitle  = 'Actualizar jornada';
require_once __DIR__.'/../config/db.php';

$mensaje = ''; $clase = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $sql = "
      UPDATE jornada
      SET  nombre_jornada = :nombre,
           hora_entrada   = :entrada,
           hora_salida    = :salida
      WHERE idjornada     = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array(
      ':nombre'  => $_POST['nombre_jornada'],
      ':entrada' => $_POST['hora_entrada'],
      ':salida'  => $_POST['hora_salida'],
      ':id'      => $_POST['idjornada']
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

<a class="btn btn-outline-primary" href="listar_jornadas.php">
  <i class="fa fa-arrow-left"></i> Volver al listado
</a>

<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';

