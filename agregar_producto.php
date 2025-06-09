<?php
include 'conexion.php';

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$idCategoria = $_POST['idCategoria'];
$idMarca = $_POST['idMarca'];
$idFabricante = $_POST['idFabricante'];
$stock = $_POST['stock'];
$precio_unitario = $_POST['precio_unitario'];
$fecha_vencimiento = $_POST['fecha_vencimiento'];

$sql = "INSERT INTO producto (nombre, descripcion, idCategoria, idMarca, idFabricante, stock, precio_unitario, fecha_vencimiento) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssiiiids", $nombre, $descripcion, $idCategoria, $idMarca, $idFabricante, $stock, $precio_unitario, $fecha_vencimiento);

if ($stmt->execute()) {
    // Redirigir a la página principal de productos (ajusta el nombre del archivo según tu proyecto)
    header("Location: formularioproducto.html?agregado=1");
    exit;
} else {
    echo "Error al agregar producto: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
