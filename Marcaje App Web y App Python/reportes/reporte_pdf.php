<?php

require_once __DIR__.'/../config/db.php';
require_once __DIR__.'/../vendor/tcpdf/tcpdf.php';

date_default_timezone_set('America/Guatemala');

/* 1. Parámetros de URL ------------------------------------------- */
$id    = isset($_GET['idempleado']) ? $_GET['idempleado'] : 0;
$desde = isset($_GET['desde'])      ? $_GET['desde']      : date('Y-m-01');
$hasta = isset($_GET['hasta'])      ? $_GET['hasta']      : date('Y-m-t');

/* 2. Datos de encabezado ----------------------------------------- */
$stmt = $conn->prepare("
  SELECT e.idempleado, e.nombre_empleado,
         d.iddept, d.nombre_dept,
         j.nombre_jornada,
         j.hora_entrada, j.hora_salida
  FROM   empleado e
  JOIN   departamento d ON d.iddept    = e.iddept
  JOIN   jornada      j ON j.idjornada = e.idjornada
  WHERE  e.idempleado = :id");
$stmt->execute([':id'=>$id]);
$info = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$info){ die('Empleado no encontrado'); }

/* 3. Permisos en el rango ---------------------------------------- */
$perms = $conn->prepare("
  SELECT fecha_permiso, motivo_falta
  FROM   permiso
  WHERE  idempleado = :id
    AND  fecha_permiso BETWEEN :d AND :h");
$perms->execute([':id'=>$id, ':d'=>$desde, ':h'=>$hasta]);
$permMap = [];
foreach ($perms as $p){ $permMap[$p['fecha_permiso']] = $p['motivo_falta']; }

/* 4. Marcas de entrada / salida ---------------------------------- */
$mov = $conn->prepare("
  SELECT fecha,
         MIN(CASE WHEN tipo_marca='entrada' THEN hora END) AS entrada,
         MAX(CASE WHEN tipo_marca='salida'  THEN hora END) AS salida
  FROM   marca
  WHERE  idempleado=:id AND fecha BETWEEN :d AND :h
  GROUP  BY fecha
  ORDER  BY fecha");
$mov->execute([':id'=>$id, ':d'=>$desde, ':h'=>$hasta]);
$rows = $mov->fetchAll(PDO::FETCH_ASSOC);

/* 5. Construir tabla HTML ---------------------------------------- */
$bodyRows  = '';
$totTarde  = 0;
$totTempr  = 0;

foreach ($rows as $r){
  $entr  = $r['entrada'] ? substr($r['entrada'],0,5) : '*';
  $salir = $r['salida']  ? substr($r['salida'],0,5)  : '*';

  $tarde = ($entr!='*' && $entr>$info['hora_entrada'])
           ? (strtotime($entr)-strtotime($info['hora_entrada']))/60 : 0;
  $tempr = ($salir!='*' && $salir<$info['hora_salida'])
           ? (strtotime($info['hora_salida'])-strtotime($salir))/60 : 0;
  $trab  = ($entr!='*' && $salir!='*')
           ? gmdate('H:i', strtotime($salir)-strtotime($entr)) : '*';

  $totTarde  += $tarde;
  $totTempr  += $tempr;
  if (isset($permMap[$r['fecha']])) {
      $obs = ucfirst($permMap[$r['fecha']]);
  } elseif ($entr=='*' || $salir=='*') {
      $obs = 'Olvidó marcar';
  } else {
      $obs = '';
  }

  $bodyRows .= '
    <tr>
      <td>'.$r['fecha'].'</td>
      <td>'.$entr.'</td>
      <td>'.$salir.'</td>
      <td>'.$tarde.'</td>
      <td>'.$tempr.'</td>
      <td>'.$trab.'</td>
      <td>'.htmlspecialchars($obs).'</td>
    </tr>';
}

/* — variables simples para el encabezado — */
$empleado     = $info['idempleado'].' - '.$info['nombre_empleado'];
$departamento = $info['iddept'].' - '.$info['nombre_dept'];
$horaEnt      = substr($info['hora_entrada'],0,5);
$horaSal      = substr($info['hora_salida'],0,5);
$nombreJor    = $info['nombre_jornada'];

/* 6. HTML final --------------------------------------------------- */
$html = <<<HTML
<style>
  table {border-collapse:collapse;font-size:10.5pt;}
  th,td {border:1px solid #000;padding:4px 3px;text-align:center;}
  th {background-color:#e5e5e5;font-weight:bold;}
  .encab {font-size:11pt;margin-bottom:6px;}
</style>

<h3 style="text-align:center;margin:0 0 10px 0;">
  Reporte de Entradas y Salidas del {$desde} al {$hasta}
</h3>

<p class="encab">
  <strong>Empleado:</strong> {$empleado}<br/>
  <strong>Departamento:</strong> {$departamento}<br/>
  <strong>Jornada:</strong> {$nombreJor} de {$horaEnt} a {$horaSal}
</p>

<table>
  <thead>
    <tr>
      <th>Fecha</th>
      <th>Entrada</th>
      <th>Salida</th>
      <th>Entrada<br/>Tarde<br/>(min)</th>
      <th>Salida<br/>Temprano<br/>(min)</th>
      <th>Horas<br/>Trabajadas</th>
      <th>Observaciones</th>
    </tr>
  </thead>
  <tbody>
    {$bodyRows}
  </tbody>
  <tfoot>
    <tr>
      <th colspan="3">Totales</th>
      <th>{$totTarde}</th>
      <th>{$totTempr}</th>
      <th colspan="2"></th>
    </tr>
  </tfoot>
</table>
HTML;

/* 7. Crear y enviar el PDF --------------------------------------- */
$pdf = new TCPDF('L','mm','A4');
$pdf->SetCreator('SIS-Asistencia');
$pdf->SetAuthor('SIS-Asistencia');
$pdf->SetTitle('Reporte de Entradas y Salidas');
$pdf->SetMargins(10,10,10,true);
$pdf->AddPage();
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('reporte_entradas_salidas.pdf','I');

