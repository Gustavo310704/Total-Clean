<?php
include 'conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['mensaje'] = "Método no permitido";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: formularioProveedor.php");
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$pais_origen = trim($_POST['pais_origen'] ?? '');

if (empty($nombre)) {
    $_SESSION['mensaje'] = "El nombre no puede estar vacío";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: formularioProveedor.php");
    exit;
}

$sql = "INSERT INTO fabricante (nombre, pais_origen) VALUES (?, ?)";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    $_SESSION['mensaje'] = "Error al preparar la consulta";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: formularioProveedor.php");
    exit;
}

$stmt->bind_param("ss", $nombre, $pais_origen);

if ($stmt->execute()) {
    $_SESSION['mensaje'] = "Fabricante agregado correctamente";
    $_SESSION['tipo_mensaje'] = "success";
} else {
    $_SESSION['mensaje'] = "Error al agregar fabricante: " . $stmt->error;
    $_SESSION['tipo_mensaje'] = "error";
}

$stmt->close();
$conexion->close();
header("Location: formularioProveedor.php");
exit;
?>