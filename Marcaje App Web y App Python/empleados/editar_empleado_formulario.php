<?php
$activePage = 'empleados';
$pageTitle  = 'Editar empleado';

require_once __DIR__.'/../config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM empleado WHERE idempleado = :id");
$stmt->execute(array(':id'=>$id));
$emp = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$emp){
  header('Location: listar_empleados.php');
  exit;
}

$deptos   = $conn->query("SELECT iddept, nombre_dept FROM departamento ORDER BY nombre_dept")
                 ->fetchAll(PDO::FETCH_ASSOC);
$jornadas = $conn->query("SELECT idjornada, nombre_jornada FROM jornada ORDER BY nombre_jornada")
                 ->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h1 class="h3 fw-bold mb-4">Editar empleado</h1>

<form action="editar_empleado.php" method="post"
      class="card shadow-sm p-4 mx-auto" style="max-width:600px;">
  <input type="hidden" name="idempleado" value="<?php echo $emp['idempleado']; ?>">

  <div class="form-floating mb-3">
    <input type="text" class="form-control" id="nombre"
           name="nombre_empleado"
           value="<?php echo htmlspecialchars($emp['nombre_empleado']); ?>"
           required>
    <label for="nombre">Nombre completo</label>
  </div>

  <div class="form-floating mb-3">
    <select class="form-select" id="departamento" name="iddept" required>
      <?php foreach ($deptos as $d):
        $sel = ($d['iddept']==$emp['iddept']) ? 'selected' : ''; ?>
        <option <?php echo $sel; ?> value="<?php echo $d['iddept']; ?>">
          <?php echo htmlspecialchars($d['nombre_dept']); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <label for="departamento">Departamento</label>
  </div>

  <div class="form-floating mb-4">
    <select class="form-select" id="jornada" name="idjornada" required>
      <?php foreach ($jornadas as $j):
        $sel = ($j['idjornada']==$emp['idjornada']) ? 'selected' : ''; ?>
        <option <?php echo $sel; ?> value="<?php echo $j['idjornada']; ?>">
          <?php echo htmlspecialchars($j['nombre_jornada']); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <label for="jornada">Jornada</label>
  </div>

  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <button class="btn btn-primary" type="submit">
      <i class="fa fa-floppy-disk me-1"></i> Guardar cambios
    </button>
    <a class="btn btn-link" href="listar_empleados.php">Cancelar</a>
  </div>
</form>

<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';

