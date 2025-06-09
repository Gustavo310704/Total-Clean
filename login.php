<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit();
}

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TotalClean - Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .logo { text-align: center; margin-bottom: 1.5rem; }
        h2 { text-align: center; color: #111c44; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        input { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 0.75rem; background-color: #111c44; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background-color: #1e2e68; }
        .error { color: #dc3545; text-align: center; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="logo">
            <h1>TotalClean</h1>
        </div>
        
        <?php if ($error): ?>
            <div class="error">
                <?php
                $mensajes_error = [
                    'campos_vacios' => 'Debe ingresar correo y contraseña',
                    'credenciales_invalidas' => 'Correo o contraseña incorrectos',
                    'error_sistema' => 'Error del sistema. Intente nuevamente'
                ];
                echo $mensajes_error[$error] ?? 'Error desconocido';
                ?>
            </div>
        <?php endif; ?>
        
        <form action="auth.php" method="POST" id="loginForm">
            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            
            <div class="form-group">
                <label for="contraseña">Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" required>
            </div>
            
            <button type="submit">Ingresar</button>
        </form>
    </div>

    <script>
        // Validación adicional del lado del cliente
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const correo = document.getElementById('correo').value.trim();
            const contraseña = document.getElementById('contraseña').value.trim();
            
            if (!correo || !contraseña) {
                e.preventDefault();
                alert('Por favor complete todos los campos');
            }
        });
    </script>
</body>
</html>