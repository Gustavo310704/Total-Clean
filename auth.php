<?php
session_start();
require 'conexion.php';

// Depuración - quitar en producción
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php?error=metodo_no_valido");
    exit();
}

// Obtener datos
$correo = trim($_POST['correo'] ?? '');
$contraseña = trim($_POST['contraseña'] ?? '');

// Validar campos
if (empty($correo) || empty($contraseña)) {
    header("Location: login.php?error=campos_vacios");
    exit();
}

try {
    // Consulta preparada
    $sql = "SELECT idUsuario, nombre, correo, contraseña, idTipoUsuario FROM usuario WHERE correo = ? LIMIT 1";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conexion->error);
    }
    
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    // Verificar si encontró el usuario
    if ($resultado->num_rows === 0) {
        header("Location: login.php?error=credenciales_invalidas");
        exit();
    }
    
    $usuario = $resultado->fetch_assoc();
    
    // Verificar contraseña (sin hash por ahora)
    if ($contraseña === $usuario['contraseña']) {
        // Crear sesión
        $_SESSION['usuario'] = [
            'id' => $usuario['idUsuario'],
            'nombre' => $usuario['nombre'],
            'correo' => $usuario['correo'],
            'tipo' => $usuario['idTipoUsuario']
        ];
        
        // Redirigir según tipo de usuario
        if ($usuario['idTipoUsuario'] == 1) { // Admin
            header("Location: dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        header("Location: login.php?error=credenciales_invalidas");
        exit();
    }
    
} catch (Exception $e) {
    error_log("Error en auth.php: " . $e->getMessage());
    header("Location: login.php?error=error_sistema");
    exit();
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conexion)) $conexion->close();
}
?>