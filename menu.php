<?php
include 'conexion.php';

// Total usuarios
$resUsuarios = $conexion->query("SELECT COUNT(*) AS total FROM usuario");
$totalUsuarios = $resUsuarios->fetch_assoc()['total'] ?? 0;

// Total productos
$resProductos = $conexion->query("SELECT COUNT(*) AS total FROM producto");
$totalProductos = $resProductos->fetch_assoc()['total'] ?? 0;

// Stock total
$resStock = $conexion->query("SELECT SUM(stock) AS total FROM producto");
$stockTotal = $resStock->fetch_assoc()['total'] ?? 0;

// Productos con alerta (stock < 5 o > 100)
$resAlertas = $conexion->query("SELECT nombre, stock FROM producto WHERE stock < 5 OR stock > 100");
$alertas = $resAlertas->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
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

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            transition: background-color 0.2s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #1e2e68;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
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

        .icon {
            width: 20px;
            height: 20px;
        }

        .main-container {
            margin-left: 220px;
            padding: 30px;
            flex: 1;
        }

        .cards {
            display: flex;
            gap: 20px;
            margin-bottom: 40px;
        }

        .card {
            flex: 1;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .card h3 {
            margin: 10px 0;
            font-size: 18px;
            color: #444;
        }

        .card span {
            font-size: 32px;
            font-weight: bold;
            color: #111c44;
        }

        .alerts {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .alerts h2 {
            margin-bottom: 20px;
            font-size: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }

        .low-stock { color: red; font-weight: bold; }
        .over-stock { color: orange; font-weight: bold; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>TotalClean</h2>
    <a href="menu.php" class="active"><i data-lucide="home" class="icon"></i>Inicio</a>
    <a href="formulariousuario.html"><i data-lucide="users" class="icon"></i>Usuarios</a>
    <a href="formularioproducto.html"><i data-lucide="package" class="icon"></i>Productos</a>
    <a href="inventario.php"><i data-lucide="box" class="icon"></i>Inventario</a>
    <a href="formularioProveedor.php"><i data-lucide="building-2" class="icon"></i>Proveedores</a>
    <hr>
    <a href="historial.html"><i data-lucide="clipboard-list" class="icon"></i>Historial</a>
    <a href="kardex.php"><i data-lucide="layers" class="icon"></i>Kardex</a>
    <a href="backup_bd.php" class="active"><i data-lucide="download" class="icon"></i>Backup</a>
    <a href="logout.php" class="logout"><i data-lucide="log-out" class="icon"></i>Salir</a>
</div>

<div class="main-container">
    <div class="cards">
        <div class="card">
            <i data-lucide="users"></i>
            <h3>Usuarios Registrados</h3>
            <span><?= $totalUsuarios ?></span>
        </div>
        <div class="card">
            <i data-lucide="package"></i>
            <h3>Productos Registrados</h3>
            <span><?= $totalProductos ?></span>
        </div>
        <div class="card">
            <i data-lucide="layers"></i>
            <h3>Stock Total</h3>
            <span><?= $stockTotal ?></span>
        </div>
    </div>

    <div class="alerts">
        <h2>⚠️ Alertas de Stock</h2>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Stock</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alertas as $producto): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td><?= $producto['stock'] ?></td>
                        <td class="<?= $producto['stock'] < 5 ? 'low-stock' : 'over-stock' ?>">
                            <?= $producto['stock'] < 5 ? 'Falta de stock' : 'Sobrestock' ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                <?php if (empty($alertas)): ?>
                    <tr><td colspan="3">Sin alertas de stock</td></tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
</body>
</html>
