<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
  header("Location: /publieventos/index.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Inventario Inteligente</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
body {
  background: #f4f6f9;
}

.card-hover:hover {
  transform: translateY(-3px);
  transition: 0.2s;
}

.border-left-success {
  border-left: 5px solid #198754;
}
.border-left-warning {
  border-left: 5px solid #ffc107;
}
.border-left-danger {
  border-left: 5px solid #dc3545;
}
</style>

</head>

<body>

<!-- NAV -->
<nav class="navbar bg-white shadow-sm mb-4">
  <div class="container d-flex justify-content-between">
    <span class="fw-bold text-primary">
      <i class="bi bi-box-seam"></i> Inventario Inteligente
    </span>

    <div>
      <a href="../../index.php" class="btn btn-outline-secondary btn-sm">← Volver</a>
      <a href="./crear.php" class="btn btn-primary btn-sm">+ Registrar</a>
    </div>
  </div>
</nav>

<div class="container">

<!-- RESUMEN -->
<div class="row mb-4" id="resumen"></div>

<!-- TABLA -->
<div class="card shadow-sm border-0 mb-4">
  <div class="card-header bg-white fw-semibold">
    📋 Lista de Productos
  </div>

  <div class="table-responsive">
    <table class="table align-middle table-hover mb-0">
      <thead class="table-light text-center">
        <tr>
          <th>ID</th>
          <th>Producto</th>
          <th>Categoría</th>
          <th>Stock</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="tabla-productos"></tbody>
    </table>
  </div>
</div>

<!-- IA -->
<div class="card shadow-sm border-0">
  <div class="card-header bg-info text-white fw-semibold">
    🧠 Análisis Inteligente
  </div>

  <div class="card-body">
    <div class="row" id="panelIA"></div>
  </div>
</div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function(){

function obtenerDatos(){

  const datos = new FormData()
  datos.append("operacion", "listar")

  fetch('../../app/controllers/producto.controller.php', {
    method: 'POST',
    body: datos
  })
  .then(res => res.json())
  .then(data => {

    const tabla = document.getElementById("tabla-productos")
    const resumen = document.getElementById("resumen")
    const panelIA = document.getElementById("panelIA")

    tabla.innerHTML = ""
    resumen.innerHTML = ""
    panelIA.innerHTML = ""

    let total = data.length
    let stock = 0
    let bajo = 0

    data.forEach(p => {

      let cantidad = parseInt(p.cantidad) || 0
      stock += cantidad
      if (cantidad <= 2) bajo++

      // COLOR ESTADO
      let estadoColor = "secondary"
      if (p.estado === "Disponible") estadoColor = "success"
      if (p.estado === "Alquilado") estadoColor = "warning"
      if (p.estado === "No Disponible") estadoColor = "danger"

      // TABLA
      tabla.innerHTML += `
      <tr class="text-center">
        <td><b>${p.IT}</b></td>

        <td class="text-start">
          <b>${p.codigo}</b><br>
          <small class="text-muted">${p.cliente}</small>
        </td>

        <td>
          <span class="badge bg-dark">${p.categoria}</span>
        </td>

        <td>
          <span class="badge bg-primary">${cantidad}</span>
        </td>

        <td>
          <span class="badge bg-${estadoColor}">
            ${p.estado}
          </span>
        </td>

        <td>
          <button class="btn btn-sm btn-outline-danger btn-eliminar" data-idproducto="${p.IT}">
            <i class="bi bi-trash"></i>
          </button>

          <a href="editar.php?id=${p.IT}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil"></i>
          </a>
        </td>
      </tr>
      `

      // IA (MEJORADO)
      let rotColor = "secondary"
      if (p.rotacion === "Alta") rotColor = "success"
      if (p.rotacion === "Media") rotColor = "warning"
      if (p.rotacion === "Baja") rotColor = "danger"

      let recColor = (p.recomendacion === "Reabastecer") ? "danger" : "success"

      panelIA.innerHTML += `
      <div class="col-md-4 mb-3">
        <div class="card shadow-sm card-hover border-left-${rotColor}">
          <div class="card-body">

            <!-- 🔥 CODIGO + CATEGORIA -->
            <div class="d-flex justify-content-between align-items-center">
              <h6 class="fw-bold text-primary mb-0">${p.codigo}</h6>
              <span class="badge bg-dark">${p.categoria}</span>
            </div>

            <small class="text-muted">${p.cliente}</small>

            <hr>

            <div class="d-flex justify-content-between mb-2">
              <span>Rotación</span>
              <span class="badge bg-${rotColor}">${p.rotacion}</span>
            </div>

            <div class="d-flex justify-content-between mb-2">
              <span>Días</span>
              <span>${p.dias}</span>
            </div>

            <div class="d-flex justify-content-between">
              <span>Acción</span>
              <span class="badge bg-${recColor}">
                ${p.recomendacion}
              </span>
            </div>

          </div>
        </div>
      </div>
      `
    })

    // RESUMEN
    resumen.innerHTML = `
    <div class="col-md-4">
      <div class="card shadow-sm text-center bg-primary text-white card-hover">
        <div class="card-body">
          <i class="bi bi-box fs-3"></i>
          <h6 class="mt-2">Total Productos</h6>
          <h2>${total}</h2>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm text-center bg-success text-white card-hover">
        <div class="card-body">
          <i class="bi bi-stack fs-3"></i>
          <h6 class="mt-2">Stock Total</h6>
          <h2>${stock}</h2>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm text-center bg-danger text-white card-hover">
        <div class="card-body">
          <i class="bi bi-exclamation-triangle fs-3"></i>
          <h6 class="mt-2">Bajo Stock</h6>
          <h2>${bajo}</h2>
        </div>
      </div>
    </div>
    `
  })
}

// ELIMINAR
document.addEventListener("click", function(e){

  if (e.target.closest(".btn-eliminar")){

    const btn = e.target.closest(".btn-eliminar")
    const id = btn.dataset.idproducto

    if (confirm("¿Eliminar producto?")){

      const datos = new FormData()
      datos.append("operacion", "eliminar")
      datos.append("IT", id)

      fetch('../../app/controllers/producto.controller.php', {
        method: 'POST',
        body: datos
      })
      .then(() => obtenerDatos())
    }
  }
})

obtenerDatos()

})
</script>

</body>
</html>