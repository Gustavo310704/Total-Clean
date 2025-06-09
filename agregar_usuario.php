<?php
include 'conexion.php';

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
$idTipoUsuario = $_POST['idTipoUsuario'];

$sql = "INSERT INTO usuario (nombre, correo, contraseña, idTipoUsuario) VALUES (?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssi", $nombre, $correo, $contrasena, $idTipoUsuario);

if ($stmt->execute()) {
    // Redirigir a la página principal de productos (ajusta el nombre del archivo según tu proyecto)
    header("Location: formulariousuario.html?agregado=1");
    exit;
} else {
    echo "Error al agregar usuario: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
