<?php
include 'conexion.php';
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración - TotalClean</title>
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
            font-size: 22px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar a:hover, .sidebar a.active {
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
            padding: 30px;
            flex: 1;
        }
        .form-container, .table-container {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .section-title {
            color: #111c44;
            margin: 30px 0 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #111c44;
        }
        form label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }
        form input, form select, form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        form input[type="submit"] {
            background-color: #111c44;
            color: white;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
        }
        form input[type="submit"]:hover {
            background-color: #1e2e68;
        }
        .alert {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .btn-warning, .btn-danger {
            padding: 5px 10px;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        .btn-warning { background-color: #f0ad4e; }
        .btn-danger { background-color: #dc3545; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .tab-buttons {
            display: flex;
            margin-bottom: 20px;
        }
        .tab-btn {
            padding: 10px 20px;
            background: #ddd;
            border: none;
            cursor: pointer;
        }
        .tab-btn.active {
            background: #111c44;
            color: white;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
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
    <a href="backup_bd.php"><i data-lucide="download" class="icon"></i>Backup</a>
    <a href="logout.php" class="logout"><i data-lucide="log-out" class="icon"></i>Salir</a>
</div>

<div class="main-container">
    <!-- Sistema de pestañas -->
    <div class="tab-buttons">
        <button class="tab-btn active" data-tab="proveedores">Proveedores</button>
        <button class="tab-btn" data-tab="marcas">Marcas</button>
        <button class="tab-btn" data-tab="fabricantes">Fabricantes</button>
        <button class="tab-btn" data-tab="categorias">Categorías</button>
    </div>

    <!-- Mensajes de operaciones -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?= $_SESSION['tipo_mensaje'] === 'error' ? 'danger' : 'success' ?>">
            <?= htmlspecialchars($_SESSION['mensaje']) ?>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
    <?php endif; ?>

    <!-- Pestaña Proveedores -->
    <div id="proveedores" class="tab-content active">
        <div class="form-container">
            <form action="agregar_proveedor.php" method="POST">
                <h3>Registrar Nuevo Proveedor</h3>
                <label for="razon_social">Razón Social:</label>
                <input type="text" id="razon_social" name="razon_social" required>

                <label for="ruc">RUC:</label>
                <input type="text" id="ruc" name="ruc" maxlength="11" required>

                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion">

                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono">

                <input type="submit" value="Agregar Proveedor">
            </form>
        </div>

        <div class="table-container">
            <table id="tablaProveedores" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Razón Social</th>
                        <th>RUC</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Pestaña Marcas -->
    <div id="marcas" class="tab-content">
        <div class="form-container">
            <form action="agregar_marca.php" method="POST" id="formMarca" enctype="multipart/form-data">
    <div class="form-group">
        <label for="marca_nombre">Nombre de la Marca:</label>
        <input type="text" id="marca_nombre" name="marca_nombre" required>
    </div>
    <input type="submit" value="Agregar Marca">
</form>
        </div>

        <div class="table-container">
            <table id="tablaMarcas" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Pestaña Fabricantes -->
    <div id="fabricantes" class="tab-content">
        <div class="form-container">
            <form action="agregar_fabricante.php" method="POST">
                <h3>Registrar Nuevo Fabricante</h3>
                <label for="nombre_fabricante">Nombre:</label>
                <input type="text" id="nombre_fabricante" name="nombre" required>

                <label for="pais_origen">País de Origen:</label>
                <input type="text" id="pais_origen" name="pais_origen">

                <input type="submit" value="Agregar Fabricante">
            </form>
        </div>

        <div class="table-container">
            <table id="tablaFabricantes" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>País de Origen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Pestaña Categorías -->
    <div id="categorias" class="tab-content">
        <div class="form-container">
            <form action="agregar_categoria.php" method="POST">
                <h3>Registrar Nueva Categoría</h3>
                <label for="nombre_categoria">Nombre:</label>
                <input type="text" id="nombre_categoria" name="nombre" required>

                <label for="descripcion_categoria">Descripción:</label>
                <textarea id="descripcion_categoria" name="descripcion" rows="3"></textarea>

                <input type="submit" value="Agregar Categoría">
            </form>
        </div>

        <div class="table-container">
            <table id="tablaCategorias" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    // Sistema de pestañas
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            btn.classList.add('active');
            document.getElementById(btn.dataset.tab).classList.add('active');
        });
    });

    // DataTables y funciones AJAX
    $(document).ready(function() {
        // Configuración común para todas las tablas
        const configBase = {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            },
            dom: 'Bfrtip',
            buttons: ['excel', 'pdf', 'print']
        };

        // Tabla de Proveedores
        $('#tablaProveedores').DataTable({
            ...configBase,
            ajax: {
                url: 'obtener_proveedores.php',
                dataSrc: 'data'
            },
            columns: [
                { data: 'idProveedor' },
                { data: 'razon_social' },
                { data: 'ruc' },
                { data: 'direccion' },
                { data: 'telefono' },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <a href="editar_proveedor.php?id=${row.idProveedor}" class="btn-warning">Editar</a>
                            <button class="btn-danger btnEliminar" data-id="${row.idProveedor}" data-tipo="proveedor">
                                Eliminar
                            </button>
                        `;
                    }
                }
            ]
        });

        // Tabla de Marcas
        $('#tablaMarcas').DataTable({
            ...configBase,
            ajax: {
                url: 'obtener_marcas.php',
                dataSrc: 'data'
            },
            columns: [
                { data: 'idMarca' },
                { data: 'nombre' },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <a href="editar_marca.php?id=${row.idMarca}" class="btn-warning">Editar</a>
                            <button class="btn-danger btnEliminar" data-id="${row.idMarca}" data-tipo="marca">
                                Eliminar
                            </button>
                        `;
                    }
                }
            ]
        });

        // Tabla de Fabricantes
        $('#tablaFabricantes').DataTable({
            ...configBase,
            ajax: {
                url: 'obtener_fabricantes.php',
                dataSrc: 'data'
            },
            columns: [
                { data: 'idFabricante' },
                { data: 'nombre' },
                { data: 'pais_origen' },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <a href="editar_fabricante.php?id=${row.idFabricante}" class="btn-warning">Editar</a>
                            <button class="btn-danger btnEliminar" data-id="${row.idFabricante}" data-tipo="fabricante">
                                Eliminar
                            </button>
                        `;
                    }
                }
            ]
        });

        // Tabla de Categorías
        $('#tablaCategorias').DataTable({
            ...configBase,
            ajax: {
                url: 'obtener_categoria.php',
                dataSrc: 'data'
            },
            columns: [
                { data: 'idCategoria' },
                { data: 'nombre' },
                { data: 'descripcion' },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <a href="editar_categoria.php?id=${row.idCategoria}" class="btn-warning">Editar</a>
                            <button class="btn-danger btnEliminar" data-id="${row.idCategoria}" data-tipo="categoria">
                                Eliminar
                            </button>
                        `;
                    }
                }
            ]
        });

        // Manejar eliminación genérica
        $(document).on('click', '.btnEliminar', function() {
            const id = $(this).data('id');
            const tipo = $(this).data('tipo');
            const endpoint = `eliminar_${tipo}.php`;
            
            if (confirm(`¿Está seguro de eliminar este ${tipo}?`)) {
                $.ajax({
                    url: endpoint,
                    method: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $(`#tabla${tipo.charAt(0).toUpperCase() + tipo.slice(1)}s`).DataTable().ajax.reload();
                        } else {
                            alert('Error: ' + response.error);
                        }
                    },
                    error: function() {
                        alert('Error al comunicarse con el servidor');
                    }
                });
            }
        });
    });
</script>

</body>
</html>