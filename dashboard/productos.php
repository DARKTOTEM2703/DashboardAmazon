<?php
// filepath: c:\xampp\htdocs\DashboardAmazon\dashboard\productos.php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login/login.php');
    exit();
}

// Incluir conexión a la base de datos
require_once '../includes/db_connection.php';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = conectarDB();

    $nombre = limpiarDato($_POST['nombre'], $conn);
    $descripcion = limpiarDato($_POST['descripcion'], $conn);
    $precio = floatval($_POST['precio']);

    // Inicializar variables para mensajes
    $mensaje_exito = '';
    $mensaje_error = '';

    // Manejo de la foto
    $foto = 'default.jpg'; // Valor predeterminado

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../reference/img/productos/';

        // Crear el directorio si no existe
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($_FILES['foto']['name']);
        $targetFilePath = $uploadDir . $fileName;

        // Verificar que sea una imagen
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        $allowTypes = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array(strtolower($fileType), $allowTypes)) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFilePath)) {
                $foto = $fileName;
            } else {
                $mensaje_error = "Error al subir la imagen. Inténtelo de nuevo.";
            }
        } else {
            $mensaje_error = "Solo se permiten archivos de imagen JPG, JPEG, PNG y GIF.";
        }
    }

    // Si no hay error, insertar en la base de datos
    if (empty($mensaje_error)) {
        // Insertar en la base de datos
        $query = "INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssds", $nombre, $descripcion, $precio, $foto);

        if (mysqli_stmt_execute($stmt)) {
            // Éxito
            $mensaje_exito = "Producto agregado exitosamente";
        } else {
            // Error
            $mensaje_error = "Error al agregar el producto: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}

// Función para obtener productos de la base de datos
function obtenerProductos()
{
    $conn = conectarDB();
    $productos = array();

    $query = "SELECT * FROM productos WHERE activo = 1 ORDER BY id DESC";
    $resultado = mysqli_query($conn, $query);

    if ($resultado) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $productos[] = $fila;
        }
        mysqli_free_result($resultado);
    }

    mysqli_close($conn);
    return $productos;
}

$productos = obtenerProductos();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Dashboard Amazon</title>
    <link rel="stylesheet" href="../css/pages/productos-styles.css">
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

                <h1 id="titulo-pagina" class="fade-in">AGREGAR PRODUCTOS</h1>

                <div class="button-group fade-in">
                    <a href="#" class="btn-agregar" id="btn-mostrar-agregar">Agregar Productos</a>
                    <a href="#" class="btn-ver" id="btn-mostrar-ver">Ver Productos</a>
                </div>

                <!-- Formulario para agregar productos -->
                <div id="seccion-agregar" class="slide-in">
                    <form action="productos.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nombre">Nombre del Producto</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripcion del Producto</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                        </div>

                        <div class="form-group">
                            <label for="precio">Precio del Producto</label>
                            <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                        </div>

                        <div class="form-group">
                            <label for="foto">Subir Foto del Producto</label>
                            <input type="file" id="foto" name="foto" accept="image/*">
                        </div>

                        <button type="submit" class="btn-guardar">Guardar</button>
                    </form>
                </div>

                <!-- Tabla para ver productos -->
                <div id="seccion-ver" style="display: none;">
                    <div id="loading-indicator" class="text-center" style="display: none;">
                        <div class="loading"></div>
                        <p>Cargando productos...</p>
                    </div>
                    <table class="productos-table">
                        <thead>
                            <tr>
                                <th class="col-id">ID</th>
                                <th class="col-foto">FOTO</th>
                                <th class="col-nombre">Nombre Producto</th>
                                <th class="col-descripcion">Descripcion</th>
                                <th class="col-precio">Precio</th>
                                <th class="col-eliminar">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-datos-productos">
                            <!-- Los datos de los productos se cargarán dinámicamente aquí -->
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
        const productos = <?php echo json_encode($productos); ?>;
    </script>

    <!-- Incluir el archivo JavaScript externo -->
    <script src="../js/productos.js"></script>
</body>

</html>