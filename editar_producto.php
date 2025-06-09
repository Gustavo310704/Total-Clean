<?php
$conexion = new mysqli("localhost", "root", "", "totalcleanbd");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$idProducto = $_GET['id'] ?? null;

if (!$idProducto) {
    die("ID de producto no proporcionado.");
}

// Si enviaron el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $idCategoria = $_POST['idCategoria'];
    $idMarca = $_POST['idMarca'];
    $idFabricante = $_POST['idFabricante'];
    $stock = $_POST['stock'];
    $precio_unitario = $_POST['precio_unitario'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];

    $sql = "UPDATE producto 
            SET nombre=?, descripcion=?, idCategoria=?, idMarca=?, idFabricante=?, stock=?, precio_unitario=?, fecha_vencimiento=? 
            WHERE idProducto=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssiiiidsi", $nombre, $descripcion, $idCategoria, $idMarca, $idFabricante, $stock, $precio_unitario, $fecha_vencimiento, $idProducto);

    if ($stmt->execute()) {
        header("Location: formularioproducto.html?actualizado=1");
        exit;
    } else {
        $error = "Error al actualizar: " . $stmt->error;
    }

    $stmt->close();
}

// Obtener datos actuales del producto
$sql = "SELECT * FROM producto WHERE idProducto=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idProducto);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    die("Producto no encontrado.");
}

$producto = $resultado->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: auto;
        }
        form label {
            display: block;
            margin-top: 15px;
        }
        form input, form select, form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        form button {
            background-color: #111c44;
            color: #fff;
            padding: 10px 20px;
            border: none;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <h2>Editar Producto</h2>
    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>

<label for="idCategoria">Categoría:</label>
            <select id="idCategoria" name="idCategoria" required>
                <option value="">Seleccione una categoría</option>
                <option value="1">Detergentes</option>
                <option value="2">Limpiadores</option>
                <option value="3">Multiuso</option>
                <option value="4">Desinfectantes</option>
                <option value="5">Desengrasantes</option>
            </select>

<label for="idMarca">Seleccionar marca:</label>
       <select name="idMarca" id="idMarca" required>
            <option value="1" <?= $producto['idMarca'] == 1 ? 'selected' : '' ?>Bolivar</option>
            <option value="2" <?= $producto['idMarca'] == 2 ? 'selected' : '' ?>Sapolio</option>
            <option value="3" <?= $producto['idMarca'] == 3 ? 'selected' : '' ?>CIF</option>
            <option value="4" <?= $producto['idMarca'] == 3 ? 'selected' : '' ?>Glade</option>
            <option value="5" <?= $producto['idMarca'] == 3 ? 'selected' : '' ?>Downy</option>
        </select>

            <label for="idFabricante">Seleccionar Fabricante:</label>
<select name="idFabricante" id="idFabricante" required>
    <option value="1" <?= ($producto['idFabricante'] ?? null) == 1 ? 'selected' : '' ?>PyG</option>
    <option value="2" <?= ($producto['idFabricante'] ?? null) == 2 ? 'selected' : '' ?>Intradevco</option>
    <option value="3" <?= ($producto['idFabricante'] ?? null) == 3 ? 'selected' : '' ?>Industrias del Epino</option>
    <option value="4" <?= ($producto['idFabricante'] ?? null) == 4 ? 'selected' : '' ?>Papelera Reyes</option>
</select>


        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" value="<?= $producto['stock'] ?>" required>

        <label for="precio_unitario">Precio Unitario:</label>
        <input type="number" step="0.01" name="precio_unitario" id="precio_unitario" value="<?= $producto['precio_unitario'] ?>" required>

        <label for="fecha_vencimiento">Fecha de Vencimiento:</label>
        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" value="<?= $producto['fecha_vencimiento'] ?>" required>

        <button type="submit">Guardar Cambios</button>
    </form>

</body>
</html>
