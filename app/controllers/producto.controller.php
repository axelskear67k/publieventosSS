<?php
require_once '../models/Producto.php';

$producto = new Producto();

if (isset($_POST['operacion'])) {

  switch ($_POST['operacion']) {

    case 'listar':
  $registros = $producto->listar();

  foreach ($registros as &$p) {

    // 🔥 ROTACIÓN
    $p['rotacion'] = $producto->calcularRotacion($p['cantidad']);

    // 🔥 DÍAS RESTANTES
    $p['dias'] = $producto->calcularDias($p['fecha_inicio'], $p['fecha_termino']);

    // 🔥 RECOMENDACIÓN
    $p['recomendacion'] = $producto->recomendacion($p['dias'], $p['cantidad']);
  }

    echo json_encode($registros);
    break;

    case 'registrar':

      $datos = [
        "codigo"        => $_POST['codigo'],
        "cliente"       => $_POST['cliente'],
        "categoria"     => $_POST['categoria'],
        "ubicacion"     => $_POST['ubicacion'],
        "medida"        => $_POST['medida'],
        "cantidad"      => $_POST['cantidad'],
        "fecha_inicio"  => $_POST['fecha_inicio'],
        "fecha_termino" => $_POST['fecha_termino'],
        "estado"        => $_POST['estado']
      ];

      $idobtenido = $producto->registrar($datos);
      echo json_encode(["id" => $idobtenido]);
    break;

    case 'actualizar':

      $datos = [
        "codigo"        => $_POST['codigo'],
        "cliente"       => $_POST['cliente'],
        "categoria"     => $_POST['categoria'],
        "ubicacion"     => $_POST['ubicacion'],
        "medida"        => $_POST['medida'],
        "cantidad"      => $_POST['cantidad'],
        "fecha_inicio"  => $_POST['fecha_inicio'],
        "fecha_termino" => $_POST['fecha_termino'],
        "estado"        => $_POST['estado'],
        "IT"            => $_POST['IT']
      ];

      $afectados = $producto->actualizar($datos);
      echo json_encode(["afectados" => $afectados]);
    break;

    case 'eliminar':
      $afectados = $producto->eliminar($_POST['IT']);
      echo json_encode(['afectados' => $afectados]);
    break;

    case 'buscarPorId':
      echo json_encode($producto->buscarPorId($_POST['IT']));
    break;

    case 'buscarPorCategoria':
      echo json_encode($producto->buscarPorCategoria($_POST['categoria']));
    break;

    //  NECESARIO PARA EDITAR
    case 'obtener':
      echo json_encode($producto->obtener($_POST['IT']));
    break;

    case 'estadisticas':

      $data = [
        "general" => $producto->estadisticas(),
        "categorias" => $producto->categorias()->fetchAll(PDO::FETCH_ASSOC),
        "estados" => $producto->estados()->fetchAll(PDO::FETCH_ASSOC)
      ];

      echo json_encode($data);

    break;
  }
}