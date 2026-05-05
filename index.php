<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /publieventos/app/auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Publieventos | Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
  background: #f5f7fb;
}

/* NAV */
.navbar{
  padding: 12px 0;
}

/* BOTONES PRO */
.btn-lg{
  border-radius: 12px;
  font-weight: 500;
}

/* HERO */
.hero-title{
  font-weight: 800;
  letter-spacing: -0.5px;
}

.hero-text{
  color: #6c757d;
  font-size: 1.05rem;
}

/* CARD IMAGE */
.hero-card{
  border: 0;
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

/* BUTTON HOVER */
.btn:hover{
  transform: translateY(-2px);
  transition: .2s;
}
</style>

</head>

<body class="d-flex flex-column min-vh-100">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark shadow-sm">
  <div class="container d-flex justify-content-between align-items-center">

    <div class="d-flex align-items-center gap-2">
      <i class="bi bi-truck fs-4 text-white"></i>
      <span class="fw-bold fs-5 text-white">PUBLIEVENTOS</span>
    </div>

    <div class="text-white d-flex align-items-center gap-3">

      <span class="small">
        👤 <b><?php echo $_SESSION['usuario']; ?></b>
      </span>

      <span class="badge bg-secondary">
        <?php echo $_SESSION['rol']; ?>
      </span>

      <a href="/publieventos/app/auth/logout.php" class="btn btn-danger btn-sm">
        <i class="bi bi-box-arrow-right"></i>
      </a>

    </div>

  </div>
</nav>

<!-- HERO -->
<header class="py-5">
  <div class="container">

    <div class="row align-items-center g-4">

      <!-- TEXTO -->
      <div class="col-md-6">

        <h1 class="hero-title display-5 mb-3">
          Sistema de Inventario 
        </h1>

        <p class="hero-text mb-4">
          Controla productos, gestiona stock y organiza tu inventario desde un solo sistema.
        </p>

        <!-- BOTONES -->
        <div class="d-flex flex-wrap gap-3">

          <?php if($_SESSION['rol'] == 'admin'): ?>
          <a href="views/productos/crear.php" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle"></i> Registrar
          </a>
          <?php endif; ?>

          <a href="views/productos/buscar.php" class="btn btn-outline-secondary btn-lg">
            <i class="bi bi-search"></i> Buscar
          </a>

          <?php if($_SESSION['rol'] == 'admin'): ?>

          <a href="views/productos/listar.php" class="btn btn-primary btn-lg">
            <i class="bi bi-list-ul"></i> Inventario
          </a>

          <a href="views/productos/estadistica.php" class="btn btn-warning btn-lg">
            <i class="bi bi-bar-chart"></i> Estadísticas
          </a>

          <?php endif; ?>

        </div>

      </div>

      <!-- IMAGEN -->
      <div class="col-md-6">

        <div class="hero-card">
          <img src="./public/images/WhatsApp Image 2026-03-05 at 11.33.50.jpeg"
               class="img-fluid w-100"
               alt="Publieventos">
        </div>

      </div>

    </div>

  </div>
</header>

<!-- FOOTER -->
<footer class="mt-auto bg-dark text-white text-center py-3">
  © 2026 Publieventos - Sistema de Inventario
</footer>

</body>
</html>