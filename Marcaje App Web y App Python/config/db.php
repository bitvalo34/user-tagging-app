<?php
$host = 'localhost';
$port = '5433';
$dbname = 'Proj2_CC5';
$user = 'administrador';
$password = 'codypumpum123';

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexión exitosa";
} catch (PDOException $e) {
    die("Error al conectar con PostgreSQL (Administrador): " . $e->getMessage());
}
?>