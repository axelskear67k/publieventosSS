<?php
$id = isset($_GET['id']) ? $_GET['id'] : 0;

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
<title>Editar Producto</title>

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
      <i class="bi bi-pencil-square"></i> Editar Producto
    </span>

    <div class="d-flex gap-2">
      <a href="../../index.php" class="btn btn-outline-secondary btn-sm">
        ← Inicio
      </a>

      <a href="./listar.php" class="btn btn-dark btn-sm">
        <i class="bi bi-list"></i> Lista
      </a>
    </div>

  </div>
</nav>

<div class="container">

<!-- FORM CARD -->
<div class="card shadow-sm border-0 card-hover">

  <div class="card-header bg-white fw-semibold">
    📝 Actualizar información del producto
  </div>

  <div class="card-body">

    <form id="form-editar">

      <input type="hidden" id="IT" value="<?php echo $id; ?>">

      <div class="row">

        <!-- Código -->
        <div class="col-md-6 mb-3">
          <label class="form-label">Código</label>
          <input type="text" id="codigo" class="form-control" required>
        </div>

        <!-- Cliente -->
        <div class="col-md-6 mb-3">
          <label class="form-label">Cliente</label>
          <input type="text" id="cliente" class="form-control" required>
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
          <input type="text" id="ubicacion" class="form-control" required>
        </div>

        <!-- Medida -->
        <div class="col-md-4 mb-3">
          <label class="form-label">Medida</label>
          <input type="text" id="medida" class="form-control" required>
        </div>

        <!-- Cantidad -->
        <div class="col-md-4 mb-3">
          <label class="form-label">Cantidad</label>
          <input type="number" id="cantidad" class="form-control" required>
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

      <!-- BOTÓN -->
      <div class="text-end mt-3">
        <button class="btn btn-primary">
          <i class="bi bi-save"></i> Actualizar
        </button>
      </div>

    </form>

  </div>
</div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function(){

const id = document.querySelector("#IT").value

// =====================
// OBTENER DATOS
// =====================
function obtenerProducto(){

  const datos = new FormData()
  datos.append("operacion", "obtener")
  datos.append("IT", id)

  fetch('../../app/controllers/producto.controller.php',{
    method:'POST',
    body:datos
  })
  .then(res => res.json())
  .then(data => {

    if(data.length > 0){
      const p = data[0]

      document.querySelector("#codigo").value = p.codigo
      document.querySelector("#cliente").value = p.cliente
      document.querySelector("#categoria").value = p.categoria
      document.querySelector("#ubicacion").value = p.ubicacion
      document.querySelector("#medida").value = p.medida
      document.querySelector("#cantidad").value = p.cantidad
      document.querySelector("#fecha_inicio").value = p.fecha_inicio
      document.querySelector("#fecha_termino").value = p.fecha_termino
      document.querySelector("#estado").value = p.estado
    }

  })

}

// =====================
// ACTUALIZAR
// =====================
document.querySelector("#form-editar")
.addEventListener("submit", function(e){
  e.preventDefault()

  if(confirm("¿Deseas actualizar este producto?")){
    actualizarProducto()
  }

})

function actualizarProducto(){

  const datos = new FormData()

  datos.append("operacion","actualizar")
  datos.append("IT", id)
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

    alert("✅ Producto actualizado correctamente")
    window.location.href = "listar.php"

  })

}

obtenerProducto()

})
</script>

</body>
</html>