<?php
session_start();

// Si ya está logueado, redirige al dashboard
if (isset($_SESSION['usuario'])) {
    header('Location: ../dashboard/dashboard.php');
    exit();
}

// Procesar el formulario
$error = '';
$debug = ''; // Variable para mostrar información de depuración (opcional)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    // Conexión a la base de datos
    $conexion = mysqli_connect('localhost', 'root', '', 'amazon_dashboard');

    // Verificar conexión
    if (mysqli_connect_errno()) {
        $error = "Error de conexión a la base de datos: " . mysqli_connect_error();
    } else {
        // Preparar consulta (previene inyección SQL)
        $stmt = mysqli_prepare($conexion, "SELECT id, email, password, nombre, apellido, rol FROM usuarios WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            // Verificar contraseña
            if (password_verify($contrasena, $fila['password'])) {
                // Credenciales correctas, crear sesión
                $_SESSION['usuario'] = $fila['email'];
                $_SESSION['usuario_id'] = $fila['id'];
                $_SESSION['nombre'] = $fila['nombre'];
                $_SESSION['apellido'] = $fila['apellido'];
                $_SESSION['rol'] = $fila['rol'];

                // Actualizar último login
                $update = mysqli_prepare($conexion, "UPDATE usuarios SET ultimo_login = NOW() WHERE id = ?");
                mysqli_stmt_bind_param($update, "i", $fila['id']);
                mysqli_stmt_execute($update);
                mysqli_stmt_close($update);

                mysqli_close($conexion);
                header('Location: ../dashboard.php');
                exit();
            } else {
                $error = 'Usuario o contraseña incorrectos';
                // Para depuración (quitar en producción):
                // $debug = "Password verificada: contraseña ingresada = '$contrasena', hash en BD = '" . $fila['password'] . "'";
            }
        } else {
            $error = 'Usuario o contraseña incorrectos';
            // $debug = "No se encontró al usuario '$usuario' en la base de datos";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Amazon Dashboard</title>
    <link rel="stylesheet" href="../css/login-styles.css">
</head>

<body>
    <div class="container">
        <!-- El logo ahora está fuera del login-box -->
        <div class="auth-container">
            <div class="logo">
                <img src="../reference/img/icono_amazon.png" alt="Amazon">
            </div>
            <div class="login-box">
                <?php if ($error): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if (!empty($debug)): ?>
                    <div class="debug"><?= htmlspecialchars($debug) ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="input-group">
                        <input type="text" name="usuario" placeholder="Correo electrónico o número de teléfono"
                            required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="contrasena" placeholder="Contraseña" required>
                    </div>
                    <div class="input-group">
                        <button type="submit">Iniciar sesión</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>