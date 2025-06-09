<?php
$conexion = new mysqli("localhost", "root", "", "totalcleanbd");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$idUsuario = $_GET['id'] ?? null;

if ($idUsuario) {
    $stmt = $conexion->prepare("DELETE FROM usuario WHERE idUsuario = ?");
    $stmt->bind_param("i", $idUsuario);

    if ($stmt->execute()) {
        echo "Usuario eliminado";
    } else {
        echo "Error al eliminar usuario: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID no proporcionado.";
}

$conexion->close();
?>
