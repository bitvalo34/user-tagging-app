<?php /*  Sidebar izquierdo (visible >= lg) */ ?>
<aside class="d-none d-lg-block col-lg-3 col-xl-2 px-0" style="background:#0f3c8c">
  <div class="list-group list-group-flush vh-100 overflow-auto sidebar-dark">
    <?php
      $menu = array(
        'dashboard'     => array('Dashboard',        'fa-chart-line',          '/PROYECTO 2 CC5/public/index.php'),
        'empleados'     => array('Empleados',        'fa-users',               '/PROYECTO 2 CC5/empleados/listar_empleados.php'),
        'departamentos' => array('Departamentos',    'fa-building',            '/PROYECTO 2 CC5/departamentos/listar_departamentos.php'),
        'jornadas'      => array('Jornadas',         'fa-clock',               '/PROYECTO 2 CC5/jornadas/listar_jornadas.php'),
        'permisos'      => array('Permisos',         'fa-person-walking-late', '/PROYECTO 2 CC5/permisos/listar_permisos.php'),
        'reportes'      => array('Reportes',         'fa-file-lines',          '/PROYECTO 2 CC5/reportes/reporte_form.php'),
      );
      foreach ($menu as $key => $item):
        list($label,$icon,$url) = $item;
        $active = ($activePage === $key) ? 'active' : '';
    ?>
      <a href="<?php echo $url; ?>"
         class="list-group-item list-group-item-action py-3 px-4 fw-semibold text-white border-0 <?php echo $active; ?> btn-locked">
         <i class="fa <?php echo $icon; ?> me-2"></i><?php echo $label; ?>
      </a>
    <?php endforeach; ?>
  </div>
</aside>
