<?php
/* ===================================================
   Dashboard principal – SIS-Asistencia
   =================================================== */

$activePage = 'dashboard';   
$pageTitle  = 'Inicio';

require_once __DIR__.'/../config/db.php';

/* ─ Stats rápidos ────────────────────────────────── */
$totalEmpleados = $conn->query("SELECT COUNT(*) FROM empleado")
                       ->fetchColumn();

$marcasHoy      = $conn->query("
    SELECT COUNT(*) FROM marca
    WHERE fecha = CURRENT_DATE
")->fetchColumn();

$tardanzasHoy   = $conn->query("
    SELECT COUNT(*) FROM permiso
    WHERE motivo_falta = 'tardanza'
      AND fecha_permiso = CURRENT_DATE
")->fetchColumn();

/* ─ Últimas marcas (5) ───────────────────────────── */
$ultimas = $conn->query("
    SELECT m.idempleado,
           e.nombre_empleado AS empleado,
           m.tipo_marca      AS tipo,
           to_char(m.fecha,'DD/MM')  AS f,
           to_char(m.hora,'HH24:MI') AS h
    FROM   marca m
    JOIN   empleado e ON e.idempleado = m.idempleado
    ORDER  BY m.fecha DESC, m.hora DESC
    LIMIT  5
")->fetchAll(PDO::FETCH_ASSOC);

/* ─ Serie últimos 7 días ────────────────────────── */
$serie = $conn->query("
  SELECT to_char(d,'DD/MM') dia,
         COALESCE(
           (SELECT COUNT(*) FROM marca WHERE fecha = d),0) total
  FROM generate_series(
         CURRENT_DATE - INTERVAL '6 days',
         CURRENT_DATE,
         '1 day') d
  ORDER BY d;
")->fetchAll(PDO::FETCH_ASSOC);

$labels = array_column($serie,'dia');
$values = array_column($serie,'total');

/* ─── HTML específico ──────────────────────────────*/
ob_start();
?>

<!-- Hero -->
<div class="py-5 text-center text-white" style="background:linear-gradient(60deg,var(--color-primary),#133a87)">
  <h1 class="display-5 fw-bold">Bienvenido a SIS-Asistencia</h1>
  <p class="lead mb-0">Panel de control rápido</p>
</div>

<!-- KPI cards -->
<div class="container my-5">
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <div class="col">
      <div class="card shadow-sm h-100 text-center border-0">
        <div class="card-body">
          <i class="fa-solid fa-users fa-2x text-primary mb-2"></i>
          <h2 class="h1 fw-bold mb-0"><?= $totalEmpleados ?></h2>
          <p class="text-muted mb-0">Empleados</p>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card shadow-sm h-100 text-center border-0">
        <div class="card-body">
          <i class="fa-solid fa-fingerprint fa-2x text-primary mb-2"></i>
          <h2 class="h1 fw-bold mb-0"><?= $marcasHoy ?></h2>
          <p class="text-muted mb-0">Marcas hoy</p>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card shadow-sm h-100 text-center border-0">
        <div class="card-body">
          <i class="fa-solid fa-person-walking-late fa-2x text-primary mb-2"></i>
          <h2 class="h1 fw-bold mb-0"><?= $tardanzasHoy ?></h2>
          <p class="text-muted mb-0">Tardanzas hoy</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Gráfico + tabla -->
<div class="container pb-5">
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header fw-semibold">Marcas últimos 7 días</div>
        <div class="card-body">
          <canvas id="chartMarcas" height="120"></canvas>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card shadow-sm h-100">
        <div class="card-header fw-semibold">Últimas 5 marcas</div>
        <div class="card-body p-0">
          <table class="table table-sm mb-0">
            <thead class="table-light">
              <tr><th>Empleado</th><th>Tipo</th><th>Fecha</th><th>Hora</th></tr>
            </thead>
            <tbody>
              <?php foreach ($ultimas as $m): ?>
              <tr>
                <td><?= htmlspecialchars($m['empleado']) ?></td>
                <td class="text-capitalize"><?= $m['tipo'] ?></td>
                <td><?= $m['f'] ?></td>
                <td><?= $m['h'] ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Links a módulos -->
<div class="bg-light py-5">
  <div class="container">
    <div class="row row-cols-1 row-cols-md-4 g-4">
      <div class="col">
        <a class="btn btn-outline-primary w-100 py-3 shadow-sm"
           href="../empleados/listar_empleados.php">
          <i class="fa-solid fa-users me-2"></i>Empleados
        </a>
      </div>
      <div class="col">
        <a class="btn btn-outline-primary w-100 py-3 shadow-sm"
           href="../departamentos/listar_departamentos.php">
          <i class="fa-solid fa-building me-2"></i>Departamentos
        </a>
      </div>
      <div class="col">
        <a class="btn btn-outline-primary w-100 py-3 shadow-sm"
           href="../jornadas/listar_jornadas.php">
          <i class="fa-solid fa-clock me-2"></i>Jornadas
        </a>
      </div>
      <div class="col">
        <a class="btn btn-outline-primary w-100 py-3 shadow-sm"
           href="../permisos/listar_permisos.php">
          <i class="fa-solid fa-person-walking-late me-2"></i>Permisos
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const ctx  = document.getElementById('chartMarcas');
new Chart(ctx,{
  type:'line',
  data:{
    labels: <?= json_encode($labels) ?>,
    datasets:[{
      label:'Marcas',
      data: <?= json_encode($values) ?>,
      fill:false,
      tension:.3,
      borderWidth:2
    }]
  },
  options:{
    plugins:{legend:{display:false}},
    scales:{y:{beginAtZero:true,precision:0}}
  }
});
</script>

<?php
$contenido = ob_get_clean();
include_once __DIR__.'/../partials/layout.php';

