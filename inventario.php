<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Inventario</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f6fa;
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

        .main-container {
            margin-left: 220px;
            padding: 20px;
            display: flex;
            gap: 20px;
        }

        .form-container {
            flex: 1;
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            height: fit-content;
        }

        .table-container {
            flex: 2;
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        form label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }

        form select, form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-accion {
            background-color: #111c44;
            color: white;
            padding: 10px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 48%;
            display: inline-block;
        }

        .btn-accion.retirar {
            background-color: #dc3545;
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

<div class="main-container">
    <div class="form-container">
        <h1>Gestión de Inventario</h1>
        <form action="procesar_inventario.php" method="POST">
            <label for="producto">Producto:</label>
            <select name="idProducto" id="producto" required>
                <option value="">Seleccione un producto</option>
                <?php
                include 'conexion.php';
                $sql = "SELECT idProducto, nombre FROM producto";
                $result = $conexion->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['idProducto']}'>{$row['nombre']}</option>";
                }
                $conexion->close();
                ?>
            </select>

            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" min="1" required>

            <input type="hidden" name="accion" id="accion" value="">

            <button type="button" class="btn-accion" onclick="setAccion('agregar')">Agregar Stock</button>
            <button type="button" class="btn-accion retirar" onclick="setAccion('retirar')">Retirar Stock</button>
        </form>
    </div>

    <div class="table-container">
        <h2>Listado de Productos</h2>
        <table id="tablaProductos" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Stock</th>
                    <th>Precio Unitario</th>
                </tr>
            </thead>
            <tbody>
                <!-- Se cargará con AJAX -->
            </tbody>
        </table>
    </div>
</div>

<script>
    function setAccion(accion) {
        document.getElementById('accion').value = accion;
        const form = document.querySelector('form');
        const formData = new FormData(form);

        fetch('procesar_inventario.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data); // muestra mensaje devuelto por PHP
            $('#tablaProductos').DataTable().ajax.reload(); // recarga la tabla sin refrescar página
            form.reset(); // limpia el formulario
        })
        .catch(error => {
            alert('Error al procesar: ' + error);
        });
    }

    $(document).ready(function() {
        $('#tablaProductos').DataTable({
            ajax: {
                url: 'listar_productos.php',
                dataSrc: ''
            },
            columns: [
                { data: 'idProducto' },
                { data: 'nombre' },
                { data: 'stock' },
                { data: 'precio_unitario' }
            ],
            dom: 'Bfrtip',
            buttons: ['excel', 'pdf', 'csv'],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            }
        });
    });
</script>


</body>
</html>
