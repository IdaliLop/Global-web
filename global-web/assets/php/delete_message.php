<?php
header('Content-Type: application/json; charset=utf-8');

// Eliminamos la validación de sesión para permitir borrado directo
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

$DB_HOST = 'sql112.infinityfree.com';
$DB_USER = 'if0_42699180';
$DB_PASS = 'XqOpfGPC6AJu7VI'; 
$DB_NAME = 'if0_42699180_restaurante_japones';

/*
    $DB_HOST = 'localhost';
    $DB_USER = 'root';
    $DB_PASS = ''; 
    $DB_NAME = 'restaurante_japones';
*/


$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión']);
    exit;
}
$mysqli->set_charset('utf8mb4');

$stmt = $mysqli->prepare("DELETE FROM mensajes WHERE id = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error en la consulta']);
    exit;
}
$stmt->bind_param('i', $id);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Mensaje eliminado']);
} else {
    echo json_encode(['success' => false, 'message' => 'No se pudo eliminar']);
}
$stmt->close();
$mysqli->close();
?>