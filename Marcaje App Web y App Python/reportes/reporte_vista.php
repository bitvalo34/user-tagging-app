<?php
$activePage = 'reportes';
$pageTitle  = 'Vista previa';
require_once __DIR__.'/../config/db.php';

$id    = $_GET['idempleado'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];

/* datos de encabezado */
$emp = $conn->prepare("
  SELECT e.idempleado, e.nombre_empleado,
         d.iddept, d.nombre_dept,
         j.nombre_jornada, j.hora_entrada, j.hora_salida
  FROM   empleado e
  JOIN   departamento d ON d.iddept   = e.iddept
  JOIN   jornada      j ON j.idjornada= e.idjornada
  WHERE  e.idempleado = :id");
$emp->execute(array(':id'=>$id));
$info = $emp->fetch(PDO::FETCH_ASSOC);

/* permisos del rango */
$perms = $conn->prepare("
  SELECT fecha_permiso, motivo_falta
  FROM   permiso
  WHERE  idempleado = :id
    AND  fecha_permiso BETWEEN :d AND :h");
$perms->execute(array(':id'=>$id, ':d'=>$desde, ':h'=>$hasta));
$permMap = array();
foreach ($perms as $p){
  $permMap[$p['fecha_permiso']] = $p['motivo_falta'];
}

/* cuerpo del reporte */
$mov = $conn->prepare("
  SELECT fecha,
         MIN(CASE WHEN tipo_marca='entrada' THEN hora END) AS entrada,
         MAX(CASE WHEN tipo_marca='salida'  THEN hora END) AS salida
  FROM   marca
  WHERE  idempleado=:id AND fecha BETWEEN :d AND :h
  GROUP  BY fecha
  ORDER  BY fecha");
$mov->execute(array(':id'=>$id, ':d'=>$desde, ':h'=>$hasta));
$rows = $mov->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>
<h1 class="h3 fw-bold mb-3 text-center">
  Reporte de Entradas y Salidas<br>
  del <?php echo $desde; ?> al <?php echo $hasta; ?>
</h1>

<p><strong>Empleado:</strong> <?php echo $info['idempleado'].' - '.htmlspecialchars($info['nombre_empleado']); ?><br>
<strong>Departamento:</strong> <?php echo $info['iddept'].' - '.htmlspecialchars($info['nombre_dept']); ?><br>
<strong>Jornada:</strong> <?php echo htmlspecialchars($info['nombre_jornada']); ?>
  de <?php echo substr($info['hora_entrada'],0,5); ?>
  a <?php echo substr($info['hora_salida'],0,5); ?></p>

<div class="table-responsive">
<table class="table table-bordered text-center align-middle">
  <thead class="table-light">
    <tr class="align-middle">
      <th>Fecha</th>
      <th>Entrada</th>
      <th>Salida</th>
      <th>Entrada<br>Tarde<br>(min)</th>
      <th>Salida<br>Temprano<br>(min)</th>
      <th>Horas<br>Trabajadas</th>
      <th>Observaciones</th>
    </tr>
  </thead>
  <tbody>
<?php
$totTarde=0; $totTempr=0;
foreach ($rows as $r):
  $entr  = $r['entrada'] ? substr($r['entrada'],0,5) : '*';
  $salir = $r['salida']  ? substr($r['salida'],0,5)  : '*';

  $tarde = ($entr!='*' && $entr>$info['hora_entrada']) ?
           (strtotime($entr)-strtotime($info['hora_entrada']))/60 : 0;
  $tempr = ($salir!='*'&& $salir<$info['hora_salida']) ?
           (strtotime($info['hora_salida'])-strtotime($salir))/60 : 0;
  $trab  = ($entr!='*'&&$salir!='*') ?
           gmdate('H:i', strtotime($salir)-strtotime($entr)) : '*';

  $totTarde   += $tarde;
  $totTempr   += $tempr;

  /* observación si existe permiso en esa fecha */
  if (isset($permMap[$r['fecha']])) {
      $obs = ucfirst($permMap[$r['fecha']]);          // motivo_falta
  } elseif ($entr=='*' || $salir=='*') {
      $obs = 'Olvidó marcar';                         // entrada o salida ausente
  } else {
      $obs = '';
  }
?>
    <tr>
      <td><?php echo $r['fecha']; ?></td>
      <td><?php echo $entr; ?></td>
      <td><?php echo $salir; ?></td>
      <td><?php echo $tarde; ?></td>
      <td><?php echo $tempr; ?></td>
      <td><?php echo $trab; ?></td>
      <td><?php echo htmlspecialchars($obs); ?></td>
    </tr>
<?php endforeach; ?>
  </tbody>
  <tfoot class="table-light fw-bold">
    <tr>
      <td colspan="3">Totales</td>
      <td><?php echo $totTarde; ?></td>
      <td><?php echo $totTempr; ?></td>
      <td colspan="2"></td>
    </tr>
  </tfoot>
</table>
</div>

<a href="reporte_pdf.php?idempleado=<?php echo $id; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>"
   class="btn btn-outline-primary mt-3" target="_blank">
   <i class="fa fa-file-pdf me-1"></i> Descargar PDF
</a>
<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';

