<?php
/* ===========================================================
   insertar_marca.php
   Recibe uid y tipo_marca por POST  ➜  inserta en tabla marca
   Sin necesidad de tabla intermedia; se usa un array UID→ID.
   =========================================================== */

date_default_timezone_set('America/Guatemala');

/* 1. Validar método ------------------------------------------------ */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);   // solo POST
    exit('Método no permitido');
}

/* 2. Obtener parámetros -------------------------------------------- */
$uid        = isset($_POST['uid'])        ? strtoupper(trim($_POST['uid'])) : '';
$tipo_marca = isset($_POST['tipo_marca']) ? strtolower(trim($_POST['tipo_marca'])) : '';

if (!$uid || !in_array($tipo_marca, array('entrada','salida'), true)) {
    http_response_code(400);
    exit('Parámetros inválidos');
}

/* 3. Mapeo UID → idempleado ---------------- */
$uidMap = array(
  '5370D724' =>  1501,
  '0295AD05' =>  1502,
  'A64EAD05' =>  1503,
  'FBD6AB05' =>  1504,
  '72B8AE05' =>  1505,
  'FE2FAE05' =>  1506,
  '214EAE05' =>  1507,
  '2419AC05' =>  1508,
  '5170A905' =>  1509,
  '6DA1B405' => 1510,
);

if (!isset($uidMap[$uid])) {
    http_response_code(404);
    exit('UID no registrado');
}

$idempleado = $uidMap[$uid];

/* 4. Conexión a PostgreSQL ---------------------------------------- */
require_once __DIR__.'/../config/dbe.php';   // $conn = PDO (...)

try {
    $sql = "
      INSERT INTO marca (idempleado, tipo_marca, fecha, hora)
      VALUES (:id, :tipo, CURRENT_DATE, CURRENT_TIME)";
    $conn->prepare($sql)->execute(array(
        ':id'   => $idempleado,
        ':tipo' => $tipo_marca,
    ));

    http_response_code(200);
    echo 'OK';

} catch (PDOException $e) {

    // 23505 = unique_violation (misma marca ya registrada hoy)
    if ($e->getCode() === '23505') {
        http_response_code(409);
        echo 'Marca duplicada';
    } else {
        http_response_code(500);
        // En producción registra en lugar de mostrar:
        // error_log($e->getMessage());
        echo 'Error DB';
    }
}
