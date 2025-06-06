<?php
$activePage = 'empleados';
$pageTitle  = 'Lista de empleados';

require_once __DIR__.'/../config/db.php';

$empleados = $conn->query("
  SELECT e.idempleado,
         e.nombre_empleado,
         d.nombre_dept      AS departamento,
         j.nombre_jornada   AS jornada,
         (SELECT COUNT(*) FROM permiso p WHERE p.idempleado = e.idempleado) AS n_perm,
         (SELECT COUNT(*) FROM marca   m WHERE m.idempleado = e.idempleado) AS n_marca
  FROM   empleado e
  JOIN   departamento d ON d.iddept    = e.iddept
  JOIN   jornada      j ON j.idjornada = e.idjornada
  ORDER  BY e.nombre_empleado
")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h1 class="h3 fw-bold mb-4">Empleados</h1>

<div class="d-flex justify-content-end mb-3">
  <a class="btn btn-accent" href="registrar_empleado_formulario.php">
    <i class="fa fa-plus me-1"></i> Nuevo empleado
  </a>
</div>

<div class="table-responsive">
<table class="table table-hover align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th>
      <th>ID</th>
      <th>Nombre</th>
      <th>Departamento</th>
      <th>Jornada</th>
      <th class="text-center">Acciones</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($empleados as $i => $e): ?>
    <?php
      $locked  = ($e['n_perm'] > 0 || $e['n_marca'] > 0);
      $tooltip = 'Permisos: '.$e['n_perm'].' | Marcas: '.$e['n_marca'];
    ?>
    <tr>
      <td><?php echo $i+1; ?></td>
      <td><?php echo $e['idempleado']; ?></td>
      <td><?php echo htmlspecialchars($e['nombre_empleado']); ?></td>
      <td><?php echo htmlspecialchars($e['departamento']); ?></td>
      <td><?php echo htmlspecialchars($e['jornada']); ?></td>
      <td class="text-center">
        <a class="btn btn-outline-primary btn-sm"
          href="editar_empleado_formulario.php?id=<?php echo $e['idempleado']; ?>"
          aria-label="Editar">
          <i class="fa fa-pen-to-square"></i>
        </a>

        <a class="btn btn-outline-danger btn-sm <?php echo $locked?'btn-locked':''; ?>"
          href="<?php echo $locked ? '#' : 'eliminar_empleado.php?id='.$e['idempleado']; ?>"
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


