<?php
include 'conexion.php';

$sql = "SELECT idProducto, nombre, stock, precio_unitario FROM producto";
$result = $conexion->query($sql);

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode($productos);

$conexion->close();
?>
