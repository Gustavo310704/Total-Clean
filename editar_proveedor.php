<?php
include 'conexion.php';

$idProveedor = $_GET['id'] ?? null;

if (!$idProveedor) die("ID no proporcionado.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $razon_social = $_POST['razon_social'];
    $ruc = $_POST['ruc'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    $sql = "UPDATE proveedor SET razon_social=?, ruc=?, direccion=?, telefono=? WHERE idProveedor=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssi", $razon_social, $ruc, $direccion, $telefono, $idProveedor);

    if ($stmt->execute()) {
        header("Location: formularioProveedor.html?actualizado=1");
        exit;
    } else {
        $error = "Error al actualizar: " . $stmt->error;
    }

    $stmt->close();
}

$sql = "SELECT * FROM proveedor WHERE idProveedor=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idProveedor);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) die("Proveedor no encontrado.");

$proveedor = $resultado->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Proveedor</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        form { background: #fff; padding: 20px; border-radius: 8px; max-width: 500px; margin: auto; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        label { display: block; margin-top: 15px; }
        input { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
        button { background-color: #111c44; color: white; padding: 10px 20px; border: none; margin-top: 20px; cursor: pointer; border-radius: 5px; }
        .error { color: red; margin-top: 10px; }
    </style>
</head>
<body>

<h2>Editar Proveedor</h2>
<?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>

<form method="POST">
    <label for="razon_social">Razón Social:</label>
    <input type="text" name="razon_social" value="<?= htmlspecialchars($proveedor['razon_social']) ?>" required>

    <label for="ruc">RUC:</label>
    <input type="text" name="ruc" value="<?= htmlspecialchars($proveedor['ruc']) ?>" required>

    <label for="direccion">Dirección:</label>
    <input type="text" name="direccion" value="<?= htmlspecialchars($proveedor['direccion']) ?>">

    <label for="telefono">Teléfono:</label>
    <input type="text" name="telefono" value="<?= htmlspecialchars($proveedor['telefono']) ?>">

    <button type="submit">Guardar Cambios</button>
</form>

</body>
</html>
