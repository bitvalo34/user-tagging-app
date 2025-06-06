<?php
$activePage = 'permisos';
$pageTitle  = 'Editar permiso';
require_once __DIR__.'/../config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM permiso WHERE idpermiso = :id");
$stmt->execute(array(':id'=>$id));
$permiso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$permiso){
  header('Location: listar_permisos.php');
  exit;
}

/* empleados para el select */
$empleados = $conn->query("
  SELECT idempleado, nombre_empleado
  FROM   empleado
  ORDER  BY nombre_empleado
")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h1 class="h3 fw-bold mb-4">Editar permiso</h1>

<form action="editar_permiso.php" method="post"
      class="card shadow-sm p-4 mx-auto" style="max-width:600px;">
  <input type="hidden" name="idpermiso" value="<?php echo $permiso['idpermiso']; ?>">

  <div class="form-floating mb-3">
    <select class="form-select" id="empleado" name="idempleado" required>
      <?php foreach ($empleados as $e):
        $sel = ($e['idempleado']==$permiso['idempleado']) ? 'selected':'';
      ?>
        <option <?php echo $sel; ?> value="<?php echo $e['idempleado']; ?>">
          <?php echo htmlspecialchars($e['nombre_empleado']); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <label for="empleado">Empleado</label>
  </div>

  <div class="form-floating mb-3">
    <input type="date" class="form-control" id="fecha"
           name="fecha_permiso"
           value="<?php echo $permiso['fecha_permiso']; ?>" required>
    <label for="fecha">Fecha</label>
  </div>

  <div class="form-floating mb-4">
    <select class="form-select" id="motivo" name="motivo_falta" required>
      <?php
        $motivos = array('ausencia','tardanza','salida_temprana');
        foreach ($motivos as $m):
          $sel = ($m==$permiso['motivo_falta']) ? 'selected':'';
      ?>
        <option <?php echo $sel; ?> value="<?php echo $m; ?>">
          <?php echo ucfirst(str_replace('_',' ',$m)); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <label for="motivo">Motivo</label>
  </div>

  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <button class="btn btn-primary" type="submit">
      <i class="fa fa-floppy-disk me-1"></i> Guardar cambios
    </button>
    <a class="btn btn-link" href="listar_permisos.php">Cancelar</a>
  </div>
</form>

<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';


