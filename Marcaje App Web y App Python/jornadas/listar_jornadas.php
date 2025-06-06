<?php
$activePage = 'jornadas';
$pageTitle  = 'Lista de jornadas';
require_once __DIR__.'/../config/db.php';

$jornadas = $conn->query("
  SELECT j.idjornada,
         j.nombre_jornada,
         j.hora_entrada,
         j.hora_salida,
         COUNT(e.idempleado) AS empleados
  FROM   jornada j
  LEFT JOIN empleado e ON e.idjornada = j.idjornada
  GROUP BY j.idjornada, j.nombre_jornada, j.hora_entrada, j.hora_salida
  ORDER  BY j.nombre_jornada
")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h1 class="h3 fw-bold mb-4">Jornadas</h1>

<div class="d-flex justify-content-end mb-3">
  <a class="btn btn-accent" href="crear_jornada_formulario.php">
    <i class="fa fa-plus me-1"></i> Nueva jornada
  </a>
</div>

<div class="table-responsive">
<table class="table table-hover align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th>
      <th>ID</th>
      <th>Nombre</th>
      <th>Entrada</th>
      <th>Salida</th>
      <th class="text-center">Acciones</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($jornadas as $i => $j): ?>
      <?php
       $locked  = ($j['empleados'] > 0);
       $tooltip = 'Tiene '.$j['empleados'].' empleado(s) asociado(s)';
      ?>
    <tr>
      <td><?php echo $i+1; ?></td>
      <td><?php echo $j['idjornada']; ?></td>
      <td><?php echo htmlspecialchars($j['nombre_jornada']); ?></td>
      <td><?php echo substr($j['hora_entrada'],0,5); ?></td>
      <td><?php echo substr($j['hora_salida'],0,5); ?></td>
      <td class="text-center">
        <a class="btn btn-outline-primary btn-sm"
          href="editar_jornada_formulario.php?id=<?php echo $j['idjornada']; ?>"
          aria-label="Editar">
          <i class="fa fa-pen-to-square"></i>
        </a>

        <a class="btn btn-outline-danger btn-sm <?php echo $locked?'btn-locked':''; ?>"
          href="<?php echo $locked ? '#' : 'eliminar_jornada.php?id='.$j['idjornada']; ?>"
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

