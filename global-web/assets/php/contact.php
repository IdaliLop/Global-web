<?php
header('Content-Type: application/json; charset=utf-8');

// Configuración de conexión
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


// 1. Recibir POST
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$email  = isset($_POST['email']) ? trim($_POST['email']) : '';
$mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';

// 2. Validaciones básicas
$errors = [];
if (strlen($nombre) < 2) $errors[] = 'Nombre inválido.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido.';
if (strlen($mensaje) < 6) $errors[] = 'Mensaje demasiado corto.';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// 3. Conexión segura usando bloque Try-Catch para evitar Error 500
try {
    // Intentamos conectar
    $mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $mysqli->set_charset('utf8mb4');
} catch (Exception $e) {
    // Si la contraseña es incorrecta o el host falla, capturamos el error sin colapsar
    echo json_encode(['success' => false, 'message' => 'Fallo de credenciales. Revisa tu contraseña de InfinityFree.']);
    exit;
}

// 4. Inserción de datos
try {
    $stmt = $mysqli->prepare("INSERT INTO mensajes (nombre, email, mensaje, fecha) VALUES (?, ?, ?, NOW())");
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Error estructurando la base de datos.']);
        exit;
    }

    $stmt->bind_param('sss', $nombre, $email, $mensaje);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Gracias — tu mensaje ha sido recibido. Te responderemos pronto.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo guardar el mensaje.']);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Ocurrió un error al intentar guardar el mensaje.']);
}

$mysqli->close();
?>