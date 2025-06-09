<?php
// Configura cabeceras para devolver JSON
header('Content-Type: application/json');

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "totalcleanbd");

if ($conexion->connect_error) {
    die(json_encode([]));
}

// Consulta a la tabla de usuarios
$sql = "SELECT u.idUsuario, u.nombre, u.correo, tu.descripcion AS tipoUsuario 
        FROM usuario u
        INNER JOIN tipousuario tu ON u.idTipoUsuario = tu.idTipoUsuario";

$resultado = $conexion->query($sql);

$usuarios = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $usuarios[] = $fila;
    }
}

// Devolver JSON
echo json_encode($usuarios);

$conexion->close();
?>
