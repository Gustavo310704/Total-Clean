<?php
$conexion = new mysqli("localhost", "root", "", "totalcleanbd");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$idProducto = $_GET['id'] ?? null;

if ($idProducto) {
    $stmt = $conexion->prepare("DELETE FROM producto WHERE idProducto = ?");
    $stmt->bind_param("i", $idProducto);

    if ($stmt->execute()) {
        echo "Producto eliminado";
    } else {
        echo "Error al eliminar producto: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID no proporcionado.";
}

$conexion->close();
?>
