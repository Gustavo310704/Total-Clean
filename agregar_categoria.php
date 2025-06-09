<?php
session_start();
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['mensaje'] = "Método no permitido";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: formularioProveedor.php");
    exit();
}

// Obtener datos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');

// Validaciones básicas
if (empty($nombre)) {
    $_SESSION['mensaje'] = "El nombre de la categoría es obligatorio";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: formularioProveedor.php");
    exit();
}

try {
    // Insertar nueva categoría
    $sql = "INSERT INTO categoria (nombre, descripcion) VALUES (?, ?)";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conexion->error);
    }
    
    $stmt->bind_param("ss", $nombre, $descripcion);
    
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Categoría agregada correctamente";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }
    
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error al agregar categoría: " . $e->getMessage();
    $_SESSION['tipo_mensaje'] = "error";
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conexion)) $conexion->close();
    header("Location: formularioProveedor.php");
    exit();
}
?>