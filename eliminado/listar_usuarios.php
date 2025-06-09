<?php
include 'conexion.php';

$sql = "SELECT u.idUsuario, u.nombre, u.correo, t.descripcion AS tipo 
        FROM usuario u
        JOIN tipousuario t ON u.idTipoUsuario = t.idTipoUsuario";
$result = $conexion->query($sql);

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

echo json_encode($usuarios);

$conexion->close();
?>
