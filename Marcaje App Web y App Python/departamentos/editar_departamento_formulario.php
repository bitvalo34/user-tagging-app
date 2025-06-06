<?php
$activePage = 'departamentos';
$pageTitle  = 'Editar departamento';
require_once __DIR__.'/../config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM departamento WHERE iddept = :id");
$stmt->execute(array(':id'=>$id));
$dep = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dep){
  header('Location: listar_departamentos.php');
  exit;
}

ob_start();
?>

<h1 class="h3 fw-bold mb-4">Editar departamento</h1>

<form action="editar_departamento.php" method="post"
      class="card shadow-sm p-4 mx-auto" style="max-width:500px;">
  <input type="hidden" name="iddept" value="<?php echo $dep['iddept']; ?>">

  <div class="form-floating mb-3">
    <input type="text" class="form-control" id="nombre"
           name="nombre_dept"
           value="<?php echo htmlspecialchars($dep['nombre_dept']); ?>"
           required>
    <label for="nombre">Nombre del departamento</label>
  </div>

  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <button class="btn btn-primary" type="submit">
      <i class="fa fa-floppy-disk me-1"></i> Guardar cambios
    </button>
    <a class="btn btn-link" href="listar_departamentos.php">Cancelar</a>
  </div>
</form>

<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';

