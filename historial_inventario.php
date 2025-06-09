<?php
include 'conexion.php';

$sql = "SELECT h.idHistorial, p.nombre AS producto, h.tipo_movimiento, h.cantidad, h.fecha
        FROM historial_inventario h
        JOIN producto p ON h.idProducto = p.idProducto
        ORDER BY h.fecha DESC";
$result = $conexion->query($sql);

$historial = [];
while ($row = $result->fetch_assoc()) {
    $historial[] = $row;
}

echo json_encode($historial);

$conexion->close();
?>
