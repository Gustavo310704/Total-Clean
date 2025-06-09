<?php
// Iniciar sesión y conexión
session_start();
require 'conexion.php';

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['mensaje'] = "Acceso no permitido";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: formularioProveedor.php");
    exit();
}

// Depuración: Verificar datos recibidos (puedes comentar esto después)
file_put_contents('debug_marca.log', print_r($_POST, true), FILE_APPEND);

// Verificar existencia del campo (usando el nombre correcto)
if (!array_key_exists('marca_nombre', $_POST)) {
    $_SESSION['mensaje'] = "Error: El campo 'nombre' no fue recibido en el formulario";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: formularioProveedor.php");
    exit();
}

// Obtener y limpiar el dato
$nombre = trim($_POST['marca_nombre']);

// Validación robusta
if (empty($nombre) || strlen($nombre) < 2) {
    $_SESSION['mensaje'] = "El nombre debe tener al menos 2 caracteres";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: formularioProveedor.php");
    exit();
}

try {
    // Preparar consulta segura
    $sql = "INSERT INTO marca (nombre) VALUES (?)";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error preparando consulta: " . $conexion->error);
    }
    
    // Vincular parámetro
    $stmt->bind_param("s", $nombre);
    
    // Ejecutar
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Marca registrada exitosamente";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        throw new Exception("Error ejecutando consulta: " . $stmt->error);
    }
    
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    $_SESSION['tipo_mensaje'] = "error";
} finally {
    // Liberar recursos
    if (isset($stmt)) $stmt->close();
    if (isset($conexion)) $conexion->close();
    
    // Redireccionar
    header("Location: formularioProveedor.php");
    exit();
}
?>