<?php
// filepath: c:\xampp\htdocs\DashboardAmazon\includes\db_connection.php
function conectarDB()
{
    $host = 'localhost';
    $usuario = 'root';
    $password = '';
    $database = 'amazon_dashboard';

    $conexion = mysqli_connect($host, $usuario, $password, $database);

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    mysqli_set_charset($conexion, "utf8");

    return $conexion;
}

// Función para limpiar datos de entrada
function limpiarDato($dato, $conexion = null)
{
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);

    if ($conexion) {
        $dato = mysqli_real_escape_string($conexion, $dato);
    }

    return $dato;
}
