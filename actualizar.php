<?php
$conexion = new mysqli("localhost", "root", "", "totalcleanbd");

$id = $_POST['idUsuario'];
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$idTipoUsuario = $_POST['idTipoUsuario'];

$sql = "UPDATE usuario SET nombre=?, correo=?, idTipoUsuario=? WHERE idUsuario=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssii", $nombre, $correo, $idTipoUsuario, $id);

if ($stmt->execute()) {
    echo "Usuario actualizado correctamente.";
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conexion->close();
?>
