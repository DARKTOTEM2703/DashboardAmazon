<?php
// Iniciar sesión en cada página
session_start();

// Función para verificar si el usuario ha iniciado sesión
function verificarAutenticacion()
{
    if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
        // Guardar la URL actual para redirigir después del login
        $_SESSION['redireccion_despues_login'] = $_SERVER['REQUEST_URI'];

        // Redirigir al login
        header('Location: ' . getBaseUrl() . 'login/login.php');
        exit();
    }

    return true;
}

// Obtener URL base
function getBaseUrl()
{
    $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://");
    $base_url .= $_SERVER['HTTP_HOST'];
    $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);

    // Eliminar "includes/" si estamos en ese directorio
    $base_url = str_replace('includes/', '', $base_url);

    return $base_url;
}

// Conexión a la base de datos centralizada
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

// Comprobar si el usuario actual es administrador
function esAdmin()
{
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}
