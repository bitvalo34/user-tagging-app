<?php
/*  Envoltura general: incluye <head>, header, sidebar, footer  */
if(!isset($pageTitle))  $pageTitle = 'SIS‑Asistencia';
if(!isset($activePage)) $activePage = '';
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle) ?> • SIS‑Asistencia</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <link href="/PROYECTO 2 CC5/public/assets/css/main.css" rel="stylesheet">
  <style>
    /* Utilidad para sidebar active */
    .list-group-item.active{background:var(--color-accent)!important;border:none;}
    .sidebar-dark .list-group-item{
      background:#0f3c8c;
      color:#fff;
      border:none;
    }
    .sidebar-dark .list-group-item:hover{
      background:#1347a6; 
    }
    .sidebar-dark .list-group-item{
      background:#0f3c8c;
      color:#fff;
      padding:1rem 1.25rem;   /* ↑ nuevo: 1 rem arriba/abajo */
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">
  <?php include_once __DIR__.'/header.php'; ?>

  <div class="container-fluid flex-grow-1">
    <div class="row gx-0">
      <?php include_once __DIR__.'/sidebar.php'; ?>

      <main class="col px-4 py-4">
        <?php echo isset($contenido) ? $contenido : ''; ?>
      </main>
    </div>
  </div>

  <?php include_once __DIR__.'/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var tt = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tt.forEach(function (el){ new bootstrap.Tooltip(el); });
    });
  </script>

</body>
</html>