<?php
$activePage = 'jornadas';
$pageTitle  = 'Editar jornada';
require_once __DIR__.'/../config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM jornada WHERE idjornada = :id");
$stmt->execute(array(':id'=>$id));
$jor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$jor){
  header('Location: listar_jornadas.php');
  exit;
}

ob_start();
?>

<h1 class="h3 fw-bold mb-4">Editar jornada</h1>

<form action="editar_jornada.php" method="post"
      class="card shadow-sm p-4 mx-auto" style="max-width:500px;">
  <input type="hidden" name="idjornada" value="<?php echo $jor['idjornada']; ?>">

  <div class="form-floating mb-3">
    <input type="text" class="form-control" id="nombre"
           name="nombre_jornada"
           value="<?php echo htmlspecialchars($jor['nombre_jornada']); ?>"
           required>
    <label for="nombre">Nombre</label>
  </div>

  <div class="form-floating mb-3">
    <input type="time" class="form-control" id="entrada"
           name="hora_entrada"
           value="<?php echo substr($jor['hora_entrada'],0,5); ?>" required>
    <label for="entrada">Hora de entrada</label>
  </div>

  <div class="form-floating mb-4">
    <input type="time" class="form-control" id="salida"
           name="hora_salida"
           value="<?php echo substr($jor['hora_salida'],0,5); ?>" required>
    <label for="salida">Hora de salida</label>
  </div>

  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <button class="btn btn-primary" type="submit">
      <i class="fa fa-floppy-disk me-1"></i> Guardar cambios
    </button>
    <a class="btn btn-link" href="listar_jornadas.php">Cancelar</a>
  </div>
</form>

<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';


