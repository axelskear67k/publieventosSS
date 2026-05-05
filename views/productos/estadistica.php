<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin') {
    header("Location: /publieventos/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard de Estadísticas</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
body{
  background:#f4f6f9;
}

.card-hover:hover{
  transform: translateY(-3px);
  transition:.2s;
}

.stat-icon{
  font-size:2rem;
  opacity:.8;
}
</style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar bg-white shadow-sm mb-4">
  <div class="container d-flex justify-content-between align-items-center">

    <span class="fw-bold text-primary">
      <i class="bi bi-bar-chart-fill"></i> Dashboard de Inventario
    </span>

    <a href="/publieventos/index.php" class="btn btn-outline-dark btn-sm">
      ← Volver
    </a>

  </div>
</nav>

<div class="container">

<!-- RESUMEN -->
<div class="row mb-4" id="stats"></div>

<!-- ALERTA -->
<div id="alerta" class="mb-4"></div>

<!-- CATEGORÍAS -->
<div class="card shadow-sm border-0 mb-4">
  <div class="card-header bg-white fw-semibold">
    📦 Productos por Categoría
  </div>
  <div class="card-body">
    <div class="row" id="categorias"></div>
  </div>
</div>

<!-- ESTADOS -->
<div class="card shadow-sm border-0">
  <div class="card-header bg-white fw-semibold">
    📊 Estado del Inventario
  </div>
  <div class="card-body">
    <div class="row" id="estados"></div>
  </div>
</div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function(){

const datos = new FormData()
datos.append("operacion", "listar")

fetch('../../app/controllers/producto.controller.php',{
  method:'POST',
  body:datos
})
.then(res => res.json())
.then(data => {

  let totalProductos = data.length
  let totalStock = 0
  let alerta = 0

  const categorias = {}
  const estados = {}

  data.forEach(p => {

    totalStock += parseInt(p.cantidad)

    if(parseInt(p.cantidad) <= 2){
      alerta++
    }

    // categorías
    categorias[p.categoria] = (categorias[p.categoria] || 0) + 1

    // estados
    if(!estados[p.estado]) estados[p.estado] = {}
    if(!estados[p.estado][p.categoria]) estados[p.estado][p.categoria] = 0

    estados[p.estado][p.categoria]++
  })

  // =========================
  // RESUMEN
  // =========================
  document.getElementById("stats").innerHTML = `
  
  <div class="col-md-6 mb-3">
    <div class="card shadow-sm border-0 bg-primary text-white card-hover">
      <div class="card-body text-center">
        <i class="bi bi-box-seam stat-icon"></i>
        <h6>Total Productos</h6>
        <h2>${totalProductos}</h2>
      </div>
    </div>
  </div>

  <div class="col-md-6 mb-3">
    <div class="card shadow-sm border-0 bg-success text-white card-hover">
      <div class="card-body text-center">
        <i class="bi bi-stack stat-icon"></i>
        <h6>Stock Total</h6>
        <h2>${totalStock}</h2>
      </div>
    </div>
  </div>

  `

  // =========================
  // ALERTA
  // =========================
  if(alerta > 0){
    document.getElementById("alerta").innerHTML = `
      <div class="alert alert-danger shadow-sm">
        ⚠️ <b>${alerta}</b> productos con bajo stock (≤ 2)
      </div>
    `
  } else {
    document.getElementById("alerta").innerHTML = `
      <div class="alert alert-success shadow-sm">
        ✔ Inventario en estado óptimo
      </div>
    `
  }

  // =========================
  // CATEGORÍAS
  // =========================
  let catHTML = ""

  for(let cat in categorias){
    catHTML += `
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm border-0 card-hover">
        <div class="card-body text-center">
          <h6 class="text-muted">${cat}</h6>
          <h3 class="text-primary">${categorias[cat]}</h3>
        </div>
      </div>
    </div>
    `
  }

  document.getElementById("categorias").innerHTML = catHTML

  // =========================
  // ESTADOS
  // =========================
  let estHTML = ""

  for(let est in estados){

    let color = "secondary"
    if(est === "Disponible") color = "success"
    if(est === "Alquilado") color = "warning"
    if(est === "No Disponible") color = "danger"

    let total = 0
    let detalle = ""

    for(let cat in estados[est]){
      total += estados[est][cat]

      detalle += `
        <div class="d-flex justify-content-between">
          <span>${cat}</span>
          <b>${estados[est][cat]}</b>
        </div>
      `
    }

    estHTML += `
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm border-0 card-hover">
        <div class="card-body">

          <h5 class="text-${color} text-center">${est}</h5>
          <h2 class="text-center">${total}</h2>

          <hr>

          ${detalle}

        </div>
      </div>
    </div>
    `
  }

  document.getElementById("estados").innerHTML = estHTML

})
.catch(err=>{
  console.log(err)
  alert("Error cargando dashboard")
})

})
</script>

</body>
</html>