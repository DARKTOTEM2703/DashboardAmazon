<?php

session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login/login.php');
    exit();
}

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aquí iría el código para procesar el formulario
    // Conectar a la base de datos
    // Guardar la información del usuario
    // Generar hash de la contraseña, etc.
}

// Función para obtener usuarios de la base de datos
function obtenerUsuarios()
{
    // Aquí colocarías tu conexión a la base de datos
    // Por ahora, devolvemos datos de ejemplo
    return [
        [
            'id' => 1,
            'nombre' => 'Juan Pérez',
            'email' => 'juan.perez@ejemplo.com',
            'rol' => 'Administrador',
            'ultimo_login' => '2023-05-15 14:30:22'
        ],
        [
            'id' => 2,
            'nombre' => 'María López',
            'email' => 'maria.lopez@ejemplo.com',
            'rol' => 'Editor',
            'ultimo_login' => '2023-05-14 09:45:10'
        ],
        [
            'id' => 3,
            'nombre' => 'Carlos Rodríguez',
            'email' => 'carlos.rodriguez@ejemplo.com',
            'rol' => 'Usuario',
            'ultimo_login' => '2023-05-10 16:20:05'
        ]
    ];
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