<?php
include 'conexion.php';

$filtro = $_GET['filtro'] ?? 'mensual';
$hoy = date('Y-m-d');
$inicio = '1970-01-01';

switch ($filtro) {
    case 'diario':
        $inicio = $hoy;
        break;
    case 'semanal':
        $inicio = date('Y-m-d', strtotime('monday this week'));
        break;
    case 'mensual':
        $inicio = date('Y-m-01');
        break;
}

$sql = "SELECT hi.*, p.nombre AS producto 
        FROM historial_inventario hi
        JOIN producto p ON hi.idProducto = p.idProducto
        WHERE DATE(hi.fecha) >= ?
        ORDER BY hi.fecha ASC";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $inicio);
$stmt->execute();
$resultado = $stmt->get_result();

$movimientos = [];
$stockDisponible = []; // Control PEPS por producto

while ($row = $resultado->fetch_assoc()) {
    $idProd = $row['idProducto'];
    $cantidad = $row['cantidad'];
    $tipo = $row['tipo_movimiento'];
    $fecha = $row['fecha'];
    $precio = $row['precio_unitario'];
    $producto = $row['producto'];

    if (!isset($stockDisponible[$idProd])) $stockDisponible[$idProd] = [];

    if ($tipo === 'Entrada') {
        $stockDisponible[$idProd][] = ['cantidad' => $cantidad, 'precio' => $precio];
        $movimientos[] = [
            'producto' => $producto,
            'fecha' => $fecha,
            'tipo' => 'Entrada',
            'cantidad' => $cantidad,
            'precio' => $precio,
            'total' => $cantidad * $precio
        ];
    } else {
        $cantSalida = $cantidad;
        $totalSalida = 0;
        $detalle = '';

        while ($cantSalida > 0 && count($stockDisponible[$idProd]) > 0) {
            $lote = &$stockDisponible[$idProd][0];
            $usar = min($cantSalida, $lote['cantidad']);
            $subtotal = $usar * $lote['precio'];
            $totalSalida += $subtotal;
            $detalle .= "{$usar} x S/{$lote['precio']} = S/{$subtotal}<br>";

            $lote['cantidad'] -= $usar;
            $cantSalida -= $usar;

            if ($lote['cantidad'] <= 0) array_shift($stockDisponible[$idProd]);
        }

        $movimientos[] = [
            'producto' => $producto,
            'fecha' => $fecha,
            'tipo' => 'Salida',
            'cantidad' => $cantidad,
            'precio' => '-',
            'total' => $totalSalida,
            'detalle' => $detalle
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Kardex PEPS</title>
    <!-- DataTables + Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f5f6fa; }
               .sidebar {
        width: 220px;
        height: 100vh;
        background-color: #111c44;
        color: white;
        position: fixed;
        display: flex;
        flex-direction: column;
        padding: 20px 0;
        transition: width 0.3s ease;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 22px;
        font-weight: bold;
    }

    .sidebar a {
        color: white;
        text-decoration: none;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 16px;
        transition: background-color 0.2s ease;
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
        .icon { width: 20px; height: 20px; }
        .main { margin-left: 220px; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background: #111c44; color: white; }
        select { padding: 6px; margin-bottom: 20px; }
        h1 { margin-bottom: 10px; }
    </style>
</head>
<script>
$(document).ready(function() {
    $('#tablaKardex').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Exportar a Excel',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
            },
            {
                extend: 'pdfHtml5',
                text: 'Exportar a PDF',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
            },
            {
                extend: 'csvHtml5',
                text: 'Exportar a CSV',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });
});
</script>

<body>

<div class="sidebar">
    <h2>TotalClean</h2>
    <a href="menu.php"><i data-lucide="home" class="icon"></i>Inicio</a>
    <a href="formulariousuario.html"><i data-lucide="users" class="icon"></i>Usuarios</a>
    <a href="formularioproducto.html"><i data-lucide="package" class="icon"></i>Productos</a>
    <a href="inventario.php"><i data-lucide="box" class="icon"></i>Inventario</a>
    <a href="formularioProveedor.php" class="active"><i data-lucide="building-2" class="icon"></i>Proveedores</a>
    <hr>
    <a href="historial.html"><i data-lucide="clipboard-list" class="icon"></i>Historial</a>
    <a href="kardex.php"><i data-lucide="layers" class="icon"></i>Kardex</a>
    <a href="backup_bd.php" class="active"><i data-lucide="download" class="icon"></i>Backup</a>
    <a href="logout.php" class="logout"><i data-lucide="log-out" class="icon"></i>Salir</a>
</div>
  <script>
    lucide.createIcons(); // Activa todos los íconos
</script>

<div class="main">
    <h1>Kardex (Método PEPS)</h1>

    <form method="get">
        Ver historial:
        <select name="filtro" onchange="this.form.submit()">
            <option value="diario" <?= $filtro == 'diario' ? 'selected' : '' ?>>Diario</option>
            <option value="semanal" <?= $filtro == 'semanal' ? 'selected' : '' ?>>Semanal</option>
            <option value="mensual" <?= $filtro == 'mensual' ? 'selected' : '' ?>>Mensual</option>
        </select>
    </form>

    <table id="tablaKardex" class="display" style="width:100%">

        <thead>
            <tr>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Detalle / Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimientos as $m): ?>
                <tr>
                    <td><?= $m['fecha'] ?></td>
                    <td><?= $m['producto'] ?></td>
                    <td><?= $m['tipo'] ?></td>
                    <td><?= $m['cantidad'] ?></td>
                    <td><?= $m['precio'] ?></td>
                    <td><?= $m['tipo'] === 'Salida' ? $m['detalle'] . "<strong>S/{$m['total']}</strong>" : "S/{$m['total']}" ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
