<?php
$conexion = new mysqli("localhost", "root", "", "totalcleanbd");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$idUsuario = $_GET['id'] ?? null;

if (!$idUsuario) {
    die("ID de usuario no proporcionado.");
}

// Si enviaron el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $idTipoUsuario = $_POST['idTipoUsuario'];

    $sql = "UPDATE usuario SET nombre=?, correo=?, idTipoUsuario=? WHERE idUsuario=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssii", $nombre, $correo, $idTipoUsuario, $idUsuario);

if ($stmt->execute()) {
    header("Location: formulariousuario.html?actualizado=1");
    exit;
}
 else {
        $error = "Error al actualizar: " . $stmt->error;
    }

    $stmt->close();
}

// Obtener datos actuales del usuario
$sql = "SELECT * FROM usuario WHERE idUsuario=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    die("Usuario no encontrado.");
}

$usuario = $resultado->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
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
            max-width: 400px;
            margin: auto;
        }
        form label {
            display: block;
            margin-top: 15px;
        }
        form input, form select {
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

    <h2>Editar Usuario</h2>
    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

        <label for="correo">Correo:</label>
        <input type="email" name="correo" id="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>

        <label for="idTipoUsuario">Tipo de Usuario:</label>
        <select name="idTipoUsuario" id="idTipoUsuario" required>
            <option value="1" <?= $usuario['idTipoUsuario'] == 1 ? 'selected' : '' ?>>Administrador</option>
            <option value="2" <?= $usuario['idTipoUsuario'] == 2 ? 'selected' : '' ?>>Manager</option>
            <option value="3" <?= $usuario['idTipoUsuario'] == 3 ? 'selected' : '' ?>>Empleado</option>
        </select>

        <button type="submit">Guardar Cambios</button>
    </form>

</body>
</html>
