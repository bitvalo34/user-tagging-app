<?php
$activePage = 'departamentos';
$pageTitle  = 'Lista de departamentos';
require_once __DIR__.'/../config/db.php';

$deps = $conn->query("
    SELECT d.iddept,
           d.nombre_dept,
           COUNT(e.idempleado) AS empleados
    FROM   departamento d
    LEFT JOIN empleado e ON e.iddept = d.iddept
    GROUP BY d.iddept, d.nombre_dept
    ORDER  BY d.nombre_dept
")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h1 class="h3 fw-bold mb-4">Departamentos</h1>

<div class="d-flex justify-content-end mb-3">
  <a class="btn btn-accent" href="insertar_departamento_formulario.php">
    <i class="fa fa-plus me-1"></i> Nuevo departamento
  </a>
</div>

<div class="table-responsive">
<table class="table table-hover align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th>
      <th>ID</th>
      <th>Nombre</th>
      <th class="text-center">Acciones</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($deps as $i => $d): ?>
    <?php
      $locked  = ($d['empleados'] > 0);
      $tooltip = 'Tiene '.$d['empleados'].' empleado(s) asociado(s)';
    ?>
    <tr>
      <td><?php echo $i+1; ?></td>
      <td><?php echo $d['iddept']; ?></td>
      <td><?php echo htmlspecialchars($d['nombre_dept']); ?></td>
      <td class="text-center">
        <a class="btn btn-outline-primary btn-sm"
           href="editar_departamento_formulario.php?id=<?php echo $d['iddept']; ?>"
           aria-label="Editar">
          <i class="fa fa-pen-to-square"></i>
        </a>
        <a class="btn btn-outline-danger btn-sm <?php echo $locked?'btn-locked':''; ?>"
           href="<?php echo $locked? '#' : 'eliminar_departamento.php?id='.$d['iddept']; ?>"
           <?php if($locked): ?>
              data-bs-toggle="tooltip" data-bs-placement="top"
              title="<?php echo $tooltip; ?>"
           <?php endif; ?>
          aria-label="Eliminar">
         <i class="fa fa-trash"></i>
        </a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>

<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';

