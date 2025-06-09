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
$razon_social = trim($_POST['razon_social'] ?? '');
$ruc = trim($_POST['ruc'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');

// Validaciones básicas
if (empty($razon_social) || empty($ruc)) {
    $_SESSION['mensaje'] = "Razón social y RUC son obligatorios";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: formularioProveedor.php");
    exit();
}

try {
    // Insertar nuevo proveedor
    $sql = "INSERT INTO proveedor (razon_social, ruc, direccion, telefono) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conexion->error);
    }
    
    $stmt->bind_param("ssss", $razon_social, $ruc, $direccion, $telefono);
    
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Proveedor agregado correctamente";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }
    
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error al agregar proveedor: " . $e->getMessage();
    $_SESSION['tipo_mensaje'] = "error";
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conexion)) $conexion->close();
    header("Location: formularioProveedor.php");
    exit();
}
?>