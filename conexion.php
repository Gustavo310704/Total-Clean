<?php
$conexion = new mysqli("localhost", "root", "", "totalcleanbd");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
