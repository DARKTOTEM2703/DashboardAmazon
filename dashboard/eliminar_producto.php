<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
    exit();
}

// Incluir conexión a la base de datos
require_once '../includes/db_connection.php';

// Verificar que se recibió un ID de producto
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'ID de producto no válido']);
    exit();
}

$id = intval($_POST['id']);
$conn = conectarDB();

// Primero obtener información del producto para eliminar la imagen si existe
$query = "SELECT imagen FROM productos WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $imagen);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Eliminar el producto (usando borrado lógico cambiando activo a 0)
$query = "UPDATE productos SET activo = 0 WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
$resultado = mysqli_stmt_execute($stmt);

if ($resultado) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
