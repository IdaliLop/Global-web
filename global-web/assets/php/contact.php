<?php
header('Content-Type: application/json; charset=utf-8');

// Configuración de conexión — ajustar si tus credenciales son distintas
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'restaurante_japones';

// Recibir POST
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$email  = isset($_POST['email']) ? trim($_POST['email']) : '';
$mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';

$errors = [];
if (strlen($nombre) < 2) $errors[] = 'Nombre inválido.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido.';
if (strlen($mensaje) < 6) $errors[] = 'Mensaje demasiado corto.';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
    exit;
}
$mysqli->set_charset('utf8mb4');

$stmt = $mysqli->prepare("INSERT INTO mensajes (nombre, email, mensaje, fecha) VALUES (?, ?, ?, NOW())");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error preparando la consulta.']);
    exit;
}

$stmt->bind_param('sss', $nombre, $email, $mensaje);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Gracias — tu mensaje ha sido recibido. Te responderemos pronto.']);
} else {
    echo json_encode(['success' => false, 'message' => 'No se pudo guardar el mensaje.']);
}

$stmt->close();
$mysqli->close();

?>