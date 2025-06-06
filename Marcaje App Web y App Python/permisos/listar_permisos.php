<?php
$activePage = 'permisos';
$pageTitle  = 'Lista de permisos';
require_once __DIR__.'/../config/db.php';

$permisos = $conn->query("
  SELECT p.idpermiso,
         e.nombre_empleado AS empleado,
         p.motivo_falta,
         p.fecha_permiso
  FROM   permiso p
  JOIN   empleado e ON e.idempleado = p.idempleado
  ORDER  BY p.fecha_permiso DESC
")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h1 class="h3 fw-bold mb-4">Permisos</h1>

<div class="d-flex justify-content-end mb-3">
  <a class="btn btn-accent" href="registrar_permiso_formulario.php">
    <i class="fa fa-plus me-1"></i> Nuevo permiso
  </a>
</div>

<div class="table-responsive">
<table class="table table-hover align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th>
      <th>ID</th>
      <th>Empleado</th>
      <th>Motivo</th>
      <th>Fecha</th>
      <th class="text-center">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($permisos as $i => $p): ?>
    <tr>
      <td><?php echo $i+1; ?></td>
      <td><?php echo $p['idpermiso']; ?></td>
      <td><?php echo htmlspecialchars($p['empleado']); ?></td>
      <td class="text-capitalize"><?php echo htmlspecialchars($p['motivo_falta']); ?></td>
      <td><?php echo $p['fecha_permiso']; ?></td>
      <td class="text-center">
        <a class="btn btn-outline-primary btn-sm"
           href="editar_permiso_formulario.php?id=<?php echo $p['idpermiso']; ?>"
           aria-label="Editar">
          <i class="fa fa-pen-to-square"></i>
        </a>
        <a class="btn btn-outline-danger btn-sm"
           href="eliminar_permiso.php?id=<?php echo $p['idpermiso']; ?>"
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


