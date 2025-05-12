<?php
// filepath: c:\xampp\htdocs\DashboardAmazon\dashboard\usuarios.php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login/login.php');
    exit();
}

// Incluir conexión a la base de datos
require_once '../includes/db_connection.php';

// Inicializar variables para mensajes
$mensaje_exito = '';
$mensaje_error = '';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = conectarDB();

    $nombre_completo = limpiarDato($_POST['nombre'], $conn);
    $email = limpiarDato($_POST['email'], $conn);
    $password = $_POST['password'];

    // Verificar que la contraseña tenga al menos 6 caracteres
    if (strlen($password) < 6) {
        $mensaje_error = "La contraseña debe tener al menos 6 caracteres";
    } else {
        // Dividir nombre completo en nombre y apellido
        $partes = explode(' ', $nombre_completo, 2);
        $nombre = $partes[0];
        $apellido = isset($partes[1]) ? $partes[1] : '';

        // Verificar si el email ya existe
        $query = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $mensaje_error = "El email ya está registrado en el sistema";
        } else {
            // Generar hash de contraseña seguro
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insertar usuario en la base de datos
            $query = "INSERT INTO usuarios (email, password, nombre, apellido, rol) VALUES (?, ?, ?, ?, 'usuario')";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $email, $password_hash, $nombre, $apellido);

            if (mysqli_stmt_execute($stmt)) {
                $mensaje_exito = "Usuario creado exitosamente";
            } else {
                $mensaje_error = "Error al crear el usuario: " . mysqli_error($conn);
            }
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}

// Función para obtener usuarios de la base de datos
function obtenerUsuarios()
{
    $conn = conectarDB();
    $usuarios = array();

    $query = "SELECT id, email, CONCAT(nombre, ' ', apellido) as nombre, rol, ultimo_login 
             FROM usuarios 
             WHERE activo = 1 
             ORDER BY id DESC";

    $resultado = mysqli_query($conn, $query);

    if ($resultado) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $usuarios[] = $fila;
        }
        mysqli_free_result($resultado);
    }

    mysqli_close($conn);
    return $usuarios;
}

$usuarios = obtenerUsuarios();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Dashboard Amazon</title>
    <link rel="stylesheet" href="../css/pages/usuarios-styles.css">
    <link rel="stylesheet" href="../css/animations.css">
</head>

<body>
    <div class="dashboard-container">
        <!-- Header -->
        <?php include '../components/header.php'; ?>

        <div class="content-container">
            <!-- Sidebar -->
            <?php include '../components/sidebar.php'; ?>

            <!-- Contenido principal con fondo blanco -->
            <div class="main-content-area">
                <?php if (!empty($mensaje_exito)): ?>
                    <div class="alert alert-success"><?php echo $mensaje_exito; ?></div>
                <?php endif; ?>

                <?php if (!empty($mensaje_error)): ?>
                    <div class="alert alert-danger"><?php echo $mensaje_error; ?></div>
                <?php endif; ?>

                <h1 id="titulo-pagina" class="fade-in">AGREGAR USUARIOS</h1>

                <div class="button-group fade-in">
                    <a href="#" class="btn-agregar" id="btn-mostrar-agregar">Agregar Usuarios</a>
                    <a href="#" class="btn-ver" id="btn-mostrar-ver">Ver Usuarios</a>
                </div>

                <!-- Formulario para agregar usuarios -->
                <div id="seccion-agregar" class="slide-in">
                    <form action="usuarios.php" method="POST">
                        <div class="form-group">
                            <label for="nombre">Tu nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                placeholder="Nombre y apellido" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Número de celular o correo electrónico</label>
                            <input type="text" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Debe tener al menos 6 caracteres" required>
                        </div>

                        <button type="submit" class="btn-guardar">Guardar</button>
                    </form>
                </div>

                <!-- Tabla para ver usuarios -->
                <div id="seccion-ver" style="display: none;">
                    <div id="loading-indicator" class="text-center" style="display: none;">
                        <div class="loading"></div>
                        <p>Cargando usuarios...</p>
                    </div>
                    <table class="productos-table">
                        <thead>
                            <tr>
                                <th class="col-id">ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Último Login</th>
                                <th class="col-eliminar">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-datos-usuarios">
                            <!-- Los datos de los usuarios se cargarán dinámicamente aquí -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include '../components/footer.php'; ?>
    </div>

    <!-- Pasar datos PHP a JavaScript -->
    <script>
        const usuarios = <?php echo json_encode($usuarios); ?>;
    </script>

    <!-- Incluir el archivo JavaScript externo -->
    <script src="../js/usuarios.js"></script>
</body>

</html>