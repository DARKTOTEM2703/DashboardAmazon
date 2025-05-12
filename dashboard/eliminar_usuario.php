<?php
// filepath: c:\xampp\htdocs\DashboardAmazon\dashboard\eliminar_usuario.php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
    exit();
}

// Incluir conexión a la base de datos
require_once '../includes/db_connection.php';

// Verificar que se recibió un ID de usuario
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'ID de usuario no válido']);
    exit();
}

$id = intval($_POST['id']);
$conn = conectarDB();

// No permitir eliminar el propio usuario
if ($id == $_SESSION['usuario_id']) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No puedes eliminar tu propio usuario']);
    exit();
}

// Eliminar el usuario (usando borrado lógico cambiando activo a 0)
$query = "UPDATE usuarios SET activo = 0 WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
$resultado = mysqli_stmt_execute($stmt);

if ($resultado) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario: ' . mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
