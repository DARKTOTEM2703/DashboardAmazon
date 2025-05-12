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
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Dashboard Amazon</title>
    <link rel="stylesheet" href="../css/pages/productos-styles.css">
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
                <h1>AGREGAR PRODUCTOS</h1>

                <div class="button-group">
                    <a href="productos.php" class="btn-agregar">Agregar Productos</a>
                    <a href="ver_productos.php" class="btn-ver">Ver Productos</a>
                </div>

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
        </div>

        <!-- Footer -->
        <?php include '../components/footer.php'; ?>
    </div>
</body>

</html>