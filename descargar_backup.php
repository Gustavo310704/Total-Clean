<?php
$nombre = $_GET['nombre'] ?? null;
$ruta = __DIR__ . "/backups/$nombre";

if (!$nombre || !file_exists($ruta)) {
    die("Archivo no encontrado.");
}

header("Content-Type: application/sql");
header("Content-Disposition: attachment; filename=$nombre");
readfile($ruta);
exit;
?>
