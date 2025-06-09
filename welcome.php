<?php
session_start();
if(!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TotalClean - Bienvenido</title>
    <link rel="stylesheet" href="style-login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="https://via.placeholder.com/150x50?text=TotalClean+Logo" alt="TotalClean Logo" class="logo">
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?></h1>
        </div>
        
        <p>Has iniciado sesión correctamente en el sistema de TotalClean.</p>
        
        <a href="logout.php" class="login-button" style="text-decoration: none; text-align: center;">Cerrar Sesión</a>
    </div>
</body>
</html>