<?php
include 'conexion.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID no proporcionado.";
    exit;
}

$stmt = $conexion->prepare("DELETE FROM proveedor WHERE idProveedor = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Proveedor eliminado";
} else {
    echo "Error al eliminar proveedor: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
