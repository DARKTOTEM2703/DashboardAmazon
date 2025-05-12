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
    // Guardar la información del producto
    // Subir la imagen, etc.
}

// Función para obtener productos de la base de datos
function obtenerProductos()
{
    // Aquí colocarías tu conexión a la base de datos
    // Por ahora, devolvemos datos de ejemplo que coincidan con la imagen de referencia
    return [
        [
            'id' => 1,
            'nombre' => 'Bolsa de almacenamiento',
            'descripcion' => 'Amazon Basics - Bolsa de almacenamiento de compresión al vacío, paquete de 15 (2...',
            'precio' => 999,
            'imagen' => 'bolsa.jpg'
        ],
        [
            'id' => 2,
            'nombre' => 'Soporte plegable para guitarra',
            'descripcion' => 'Amazon Basics - Soporte plegable para guitarra eléctrica y acústica, en forma de A',
            'precio' => 469,
            'imagen' => 'soporte.jpg'
        ],
        [
            'id' => 3,
            'nombre' => 'Ventilador de pedestal',
            'descripcion' => 'Amazon Basics - Ventilador de pedestal, doble hoja y control remoto, 40 cm, color...',
            'precio' => 1799,
            'imagen' => 'ventilador.jpg'
        ]
    ];
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