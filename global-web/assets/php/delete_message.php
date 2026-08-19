<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
if (empty($_SESSION['admin_logged'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'restaurante_japones';

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