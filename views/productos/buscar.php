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
<title>Buscador Inteligente</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
body{
  background:#f4f6f9;
}

.card-hover:hover{
  transform: translateY(-3px);
  transition: .2s;
}

.table thead{
  background:#f1f3f5;
}

.badge-soft{
  padding:6px 10px;
  border-radius:10px;
}
</style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar bg-white shadow-sm mb-4">
  <div class="container d-flex justify-content-between align-items-center">

    <span class="fw-bold text-primary">
      <i class="bi bi-search"></i> Buscador Inteligente
    </span>

    <a href="../../index.php" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left"></i> Volver
    </a>

  </div>
</nav>

<div class="container">

<!-- RESUMEN -->
<div class="row mb-4">

  <div class="col-md-6 mb-3">
    <div class="card shadow-sm card-hover border-start border-primary border-4">
      <div class="card-body">
        <h6 class="text-muted">Búsqueda por ID</h6>
        <p class="mb-0">Consulta rápida de productos individuales</p>
      </div>
    </div>
  </div>

  <div class="col-md-6 mb-3">
    <div class="card shadow-sm card-hover border-start border-success border-4">
      <div class="card-body">
        <h6 class="text-muted">Búsqueda por Categoría</h6>
        <p class="mb-0">Listado filtrado por tipo de producto</p>
      </div>
    </div>
  </div>

</div>

<!-- BUSQUEDA POR ID -->
<div class="card shadow-sm border-0 mb-4">
  <div class="card-header bg-white fw-semibold">
    🔎 Buscar Producto por ID
  </div>

  <div class="card-body">

    <form id="form-busqueda-1">

      <label class="form-label">ID del Producto</label>

      <div class="input-group mb-3">
        <span class="input-group-text">IT</span>
        <input type="text" class="form-control" id="idbuscado" placeholder="Ingrese ID">
        <button class="btn btn-primary">
          <i class="bi bi-search"></i> Buscar
        </button>
      </div>

      <label class="form-label">Resultado</label>
      <input type="text" class="form-control bg-light" id="resultado" readonly>

    </form>

  </div>
</div>

<!-- BUSQUEDA POR CATEGORIA -->
<div class="card shadow-sm border-0 mb-4">
  <div class="card-header bg-white fw-semibold">
    📦 Buscar por Categoría
  </div>

  <div class="card-body">

    <form id="form-busqueda-2">

      <label class="form-label">Selecciona categoría</label>

      <div class="input-group">
        <select id="categorias" class="form-select">
          <option value="">Seleccione</option>
          <option value="Vallas Publicitarias">Vallas Publicitarias</option>
          <option value="Roll Screen">Roll Screen</option>
          <option value="Paneletas Publicitarias">Paneletas Publicitarias</option>
          <option value="Total Led">Total Led</option>
          <option value="Tricivallas">Tricivallas</option>
        </select>

        <button class="btn btn-success">
          <i class="bi bi-funnel"></i> Filtrar
        </button>
      </div>

    </form>

  </div>
</div>

<!-- TABLA -->
<div class="card shadow-sm border-0">
  <div class="card-header bg-white fw-semibold">
    📋 Resultados
  </div>

  <div class="table-responsive">

    <table class="table table-hover align-middle mb-0">
      <thead class="text-center">
        <tr>
          <th>IT</th>
          <th>Código</th>
          <th>Cliente</th>
          <th>Ubicación</th>
          <th>Medida</th>
          <th>Cantidad</th>
          <th>Inicio</th>
          <th>Fin</th>
          <th>Estado</th>
        </tr>
      </thead>

      <tbody id="tabla-categorias"></tbody>
    </table>

  </div>
</div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function(){

// BUSCAR POR ID
function buscarProductoID(){

  const datos = new FormData()
  datos.append("operacion", "buscarPorId")
  datos.append("IT", document.querySelector("#idbuscado").value)

  fetch('../../app/controllers/producto.controller.php', {
    method:'POST',
    body:datos
  })
  .then(res => res.json())
  .then(data => {

    if(data.length > 0){
      const p = data[0]
      document.querySelector("#resultado").value =
        `${p.codigo} - ${p.cliente} (${p.categoria})`
    }else{
      document.querySelector("#resultado").value = ""
      alert("No se encontró el producto")
    }

  })

}

// BUSCAR POR CATEGORIA
function buscarPorCategoria(){

  const datos = new FormData()
  datos.append("operacion", "buscarPorCategoria")
  datos.append("categoria", document.querySelector("#categorias").value)

  fetch('../../app/controllers/producto.controller.php', {
    method:'POST',
    body:datos
  })
  .then(res => res.json())
  .then(data => {

    const tbody = document.querySelector("#tabla-categorias")
    tbody.innerHTML = ""

    data.forEach(p => {

      let estadoColor = "secondary"
      if(p.estado === "Disponible") estadoColor = "success"
      if(p.estado === "Alquilado") estadoColor = "warning"
      if(p.estado === "No Disponible") estadoColor = "danger"

      tbody.innerHTML += `
      <tr class="text-center">
        <td><b>${p.IT}</b></td>
        <td>${p.codigo}</td>
        <td>${p.cliente}</td>
        <td>${p.ubicacion}</td>
        <td>${p.medida}</td>
        <td><span class="badge bg-primary">${p.cantidad}</span></td>
        <td>${p.fecha_inicio}</td>
        <td>${p.fecha_termino}</td>
        <td><span class="badge bg-${estadoColor}">${p.estado}</span></td>
      </tr>
      `
    })

  })

}

// EVENTOS
document.querySelector("#form-busqueda-1")
.addEventListener("submit", e=>{
  e.preventDefault()
  buscarProductoID()
})

document.querySelector("#form-busqueda-2")
.addEventListener("submit", e=>{
  e.preventDefault()
  buscarPorCategoria()
})

})
</script>

</body>
</html>