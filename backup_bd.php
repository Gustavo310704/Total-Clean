<?php
// CONFIGURACIÓN
$host = 'localhost';
$usuario = 'root';
$password = '';
$base_datos = 'totalcleanbd';
$nombre_archivo = 'backup_' . $base_datos . '_' . date('Ymd_His') . '.sql';

$mysqldump = $mysqldump = '"C:\\xampp\\mysql\\bin\\mysqldump.exe"';

$comando = $comando = "$mysqldump -u $usuario $base_datos";
;

// Ejecutar comando y guardar temporalmente
$ruta_backup = __DIR__ . "/backups/$nombre_archivo"; // asegúrate de tener una carpeta backups
$comando = "$mysqldump -u $usuario $base_datos > \"$ruta_backup\"";
exec($comando, $output, $retorno);
$exito = $retorno === 0;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Backup de Base de Datos</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f6fa;
            display: flex;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #111c44;
            color: white;
            position: fixed;
            display: flex;
            flex-direction: column;
            padding: 20px 0;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 16px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #1e2e68;
        }

        .sidebar hr {
            border: 1px solid #2e3b70;
            width: 80%;
            margin: 20px auto;
        }

        .sidebar .logout {
            margin-top: auto;
            background-color: #1f2e4d;
        }

        .main-container {
            margin-left: 220px;
            padding: 40px;
            flex: 1;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            margin: auto;
        }

        .card h1 {
            font-size: 24px;
            color: #111c44;
            margin-bottom: 20px;
        }

        .btn-descargar {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .mensaje {
            margin-top: 20px;
            font-weight: bold;
            color: <?= $exito ? 'green' : 'red' ?>;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>TotalClean</h2>
    <a href="menu.php"><i data-lucide="home" class="icon"></i>Inicio</a>
    <a href="formulariousuario.html"><i data-lucide="users" class="icon"></i>Usuarios</a>
    <a href="formularioproducto.html"><i data-lucide="package" class="icon"></i>Productos</a>
    <a href="inventario.php"><i data-lucide="box" class="icon"></i>Inventario</a>
    <a href="kardex.php"><i data-lucide="layers" class="icon"></i>Kardex</a>
    <a href="formularioProveedor.php"><i data-lucide="building-2" class="icon"></i>Proveedores</a>
    <hr>
    <a href="historial.html"><i data-lucide="clipboard-list" class="icon"></i>Historial</a>
    <a href="backup_bd.php" class="active"><i data-lucide="download" class="icon"></i>Backup</a>
    <a href="salir.html" class="logout"><i data-lucide="log-out" class="icon"></i>Salir</a>
</div>

<div class="main-container">
    <div class="card">
        <h1>Backup de la Base de Datos</h1>
        <?php if ($exito): ?>
            <a class="btn-descargar" href="descargar_backup.php?nombre=<?= urlencode($nombre_archivo) ?>">Descargar Backup</a>
        <?php else: ?>
            <p class="mensaje">❌ Error al generar el backup</p>
            <pre><?= htmlspecialchars($salida) ?></pre>
        <?php endif; ?>
    </div>
</div>

<script>lucide.createIcons();</script>
</body>
</html>
