<?php
session_start();

if ($_SESSION['rol'] != 'admin') {
    header("Location: /publieventos/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registro Inteligente</title>

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

.form-control, .form-select{
  border-radius:10px;
}

</style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar bg-white shadow-sm mb-4">
  <div class="container d-flex justify-content-between align-items-center">

    <span class="fw-bold text-primary">
      <i class="bi bi-plus-circle"></i> Registro de Productos
    </span>

    <div class="d-flex gap-2">
      <a href="../../index.php" class="btn btn-outline-secondary btn-sm">
        ← Inicio
      </a>

      <a href="./listar.php" class="btn btn-primary btn-sm">
        <i class="bi bi-list"></i> Ver Lista
      </a>
    </div>

  </div>
</nav>

<div class="container">

<!-- FORM CARD -->
<div class="card shadow-sm border-0 card-hover">

  <div class="card-header bg-white fw-semibold">
    📝 Completa la información del producto
  </div>

  <div class="card-body">

    <form id="formulario-producto">

      <div class="row">

        <!-- Código -->
        <div class="col-md-6 mb-3">
          <label class="form-label">Código</label>
          <input type="text" id="codigo" class="form-control" placeholder="Ej: P-001" required>
        </div>

        <!-- Cliente -->
        <div class="col-md-6 mb-3">
          <label class="form-label">Cliente</label>
          <input type="text" id="cliente" class="form-control" placeholder="Nombre del cliente" required>
        </div>

        <!-- Categoría -->
        <div class="col-md-6 mb-3">
          <label class="form-label">Categoría</label>
          <select id="categoria" class="form-select" required>
            <option value="">Seleccione</option>
            <option value="Vallas Publicitarias">Vallas Publicitarias</option>
            <option value="Roll Screen">Roll Screen</option>
            <option value="Paneletas Publicitarias">Paneletas Publicitarias</option>
            <option value="Total Led">Total Led</option>
            <option value="Tricivallas">Tricivallas</option>
          </select>
        </div>

        <!-- Ubicación -->
        <div class="col-md-6 mb-3">
          <label class="form-label">Ubicación</label>
          <input type="text" id="ubicacion" class="form-control" placeholder="Dirección" required>
        </div>

        <!-- Medida -->
        <div class="col-md-4 mb-3">
          <label class="form-label">Medida</label>
          <input type="text" id="medida" class="form-control" placeholder="Ej: 2m x 1m" required>
        </div>

        <!-- Cantidad -->
        <div class="col-md-4 mb-3">
          <label class="form-label">Cantidad</label>
          <input type="number" min="1" value="1" id="cantidad" class="form-control" required>
        </div>

        <!-- Estado -->
        <div class="col-md-4 mb-3">
          <label class="form-label">Estado</label>
          <select id="estado" class="form-select" required>
            <option value="">Seleccione</option>
            <option value="Disponible">Disponible</option>
            <option value="Alquilado">Alquilado</option>
            <option value="No Disponible">No Disponible</option>
          </select>
        </div>

        <!-- Fecha Inicio -->
        <div class="col-md-6 mb-3">
          <label class="form-label">Fecha Inicio</label>
          <input type="date" id="fecha_inicio" class="form-control" required>
        </div>

        <!-- Fecha Término -->
        <div class="col-md-6 mb-3">
          <label class="form-label">Fecha Término</label>
          <input type="date" id="fecha_termino" class="form-control" required>
        </div>

      </div>

      <!-- BOTONES -->
      <div class="d-flex justify-content-end gap-2 mt-3">

        <button class="btn btn-outline-secondary" type="reset">
          <i class="bi bi-x-circle"></i> Limpiar
        </button>

        <button class="btn btn-primary" type="submit">
          <i class="bi bi-save"></i> Guardar
        </button>

      </div>

    </form>

  </div>
</div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function(){

document.querySelector("#formulario-producto")
.addEventListener("submit", function(e){
  e.preventDefault()

  if(confirm("¿Deseas guardar este producto?")){
    guardarDatos()
  }

})

function guardarDatos(){

  const datos = new FormData()

  datos.append("operacion", "registrar")
  datos.append("codigo", document.querySelector("#codigo").value)
  datos.append("cliente", document.querySelector("#cliente").value)
  datos.append("categoria", document.querySelector("#categoria").value)
  datos.append("ubicacion", document.querySelector("#ubicacion").value)
  datos.append("medida", document.querySelector("#medida").value)
  datos.append("cantidad", document.querySelector("#cantidad").value)
  datos.append("fecha_inicio", document.querySelector("#fecha_inicio").value)
  datos.append("fecha_termino", document.querySelector("#fecha_termino").value)
  datos.append("estado", document.querySelector("#estado").value)

  fetch('../../app/controllers/producto.controller.php',{
    method:'POST',
    body:datos
  })
  .then(res => res.json())
  .then(data => {

    if(data.id > 0){
      document.querySelector("#formulario-producto").reset()
      alert("✅ Producto registrado correctamente")
    }else{
      alert("❌ Error al registrar producto")
    }

  })

}

})
</script>

</body>
</html>