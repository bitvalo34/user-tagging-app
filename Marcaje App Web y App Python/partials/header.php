<?php /*  Barra superior  */ ?>
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background:linear-gradient(60deg,var(--color-primary),#133a87)">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="/PROYECTO 2 CC5/public/index.php">
      <i class="fa-solid fa-fingerprint fa-lg me-2"></i> SIS‑Asistencia
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-circle-user fa-lg me-2"></i> Admin
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu">
            <li><a class="dropdown-item" href="#">Mi perfil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Cerrar sesión</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>