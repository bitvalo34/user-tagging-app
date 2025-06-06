<?php
$activePage = 'jornadas';
$pageTitle  = 'Eliminar jornada';
require_once __DIR__.'/../config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $stmt = $conn->prepare("DELETE FROM jornada WHERE idjornada = :id");
    $stmt->execute(array(':id'=>$_POST['idjornada']));
    header('Location: listar_jornadas.php?msg=deleted');
    exit;
  } catch (PDOException $e) {
    if ($e->getCode() === '23503') {
        $error = 'No puedes eliminar esta jornada porque '
               . 'hay empleados asignados a ella.';
    } else {
        $error = $e->getMessage();
    }
  }
}

ob_start();
?>

<h1 class="h3 fw-bold mb-4 text-danger">Eliminar jornada</h1>

<?php if (isset($error)): ?>
  <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php else: ?>
  <div class="alert alert-warning">
    ¿Seguro que deseas eliminar este registro? Esta acción es irreversible.
  </div>

  <form method="post" class="d-flex gap-2">
    <input type="hidden" name="idjornada" value="<?php echo htmlspecialchars($id); ?>">
    <button class="btn btn-danger" type="submit">
      <i class="fa fa-trash me-1"></i> Eliminar
    </button>
    <a class="btn btn-secondary" href="listar_jornadas.php">Cancelar</a>
  </form>
<?php endif; ?>

<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';


