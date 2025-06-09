<?php
include 'conexion.php';

$idProducto = $_POST['idProducto'];
$cantidad = intval($_POST['cantidad']);
$accion = $_POST['accion'];

if (!$idProducto || !$cantidad || !$accion) {
    die("Datos incompletos.");
}

$sql = "SELECT stock FROM producto WHERE idProducto = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idProducto);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Producto no encontrado.");
}

$stockActual = intval($row['stock']);

if ($accion == 'agregar') {
    $nuevoStock = $stockActual + $cantidad;
    $tipoMovimiento = 'Entrada';
} elseif ($accion == 'retirar') {
    if ($cantidad > $stockActual) {
        die("No hay suficiente stock.");
    }
    $nuevoStock = $stockActual - $cantidad;
    $tipoMovimiento = 'Salida';
} else {
    die("Acción inválida.");
}

// Actualizar stock
$update = $conexion->prepare("UPDATE producto SET stock = ? WHERE idProducto = ?");
$update->bind_param("ii", $nuevoStock, $idProducto);
$update->execute();

// Registrar historial
$historial = $conexion->prepare("INSERT INTO historial_inventario (idProducto, tipo_movimiento, cantidad, fecha) VALUES (?, ?, ?, NOW())");
$historial->bind_param("isi", $idProducto, $tipoMovimiento, $cantidad);
$historial->execute();

$update->close();
$historial->close();
$stmt->close();
$conexion->close();

echo "Stock actualizado correctamente.";
?>
