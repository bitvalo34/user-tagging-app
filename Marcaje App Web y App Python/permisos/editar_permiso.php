<?php
$activePage = 'permisos';
$pageTitle  = 'Actualizar permiso';
require_once __DIR__.'/../config/db.php';

$mensaje = ''; $clase = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $sql = "
      UPDATE permiso
      SET  idempleado   = :idempleado,
           fecha_permiso= :fecha,
           motivo_falta = :motivo
      WHERE idpermiso   = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array(
      ':idempleado' => $_POST['idempleado'],
      ':fecha'      => $_POST['fecha_permiso'],
      ':motivo'     => $_POST['motivo_falta'],
      ':id'         => $_POST['idpermiso']
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

<a class="btn btn-outline-primary" href="listar_permisos.php">
  <i class="fa fa-arrow-left"></i> Volver al listado
</a>

<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';

