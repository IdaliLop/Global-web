<?php
// Conexión directa a la base de datos sin validación de sesión
$DB_HOST = 'sql112.infinityfree.com';
$DB_USER = 'if0_42699180';
$DB_PASS = 'XqOpfGPC6AJu7VI'; 
$DB_NAME = 'if0_42699180_restaurante_japones';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die('Error de conexión a la base de datos: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

$result = $mysqli->query("SELECT id, nombre, email, mensaje, fecha FROM mensajes ORDER BY fecha DESC");
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin — Mensajes recibidos</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Shippori+Mincho:wght@400;500;600;700&family=EB+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Noto+Serif+JP:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/admin_panel.css">
</head>
<body>
  <div class="wrap">
    <header class="admin-head">
      <div class="brand"><span class="logo-jp">大好き</span><div>Daisuki — Panel</div></div>
      <div class="controls"><a class="btn" href="index.html">Ver sitio</a></div>
    </header>

    <main>
      <section class="card" aria-labelledby="msgs-title">
        <div class="table-wrap">
          <h1 id="msgs-title" style="margin-top:0;margin-bottom:12px">Mensajes recibidos</h1>
          <div style="margin-bottom:12px;color:#555">Aquí puedes revisar los mensajes y eliminarlos si es necesario.</div>
          <table aria-describedby="msgs-title">
            <thead>
              <tr><th style="width:60px">ID</th><th>Nombre</th><th>Email</th><th>Mensaje</th><th style="width:160px">Fecha</th><th style="width:110px">Acciones</th></tr>
            </thead>
            <tbody>
<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = (int)$row['id'];
        $nombre = htmlspecialchars($row['nombre']);
        $email = htmlspecialchars($row['email']);
        $mensaje = nl2br(htmlspecialchars($row['mensaje']));
        $fecha = htmlspecialchars($row['fecha']);
        
        echo "<tr data-id=\"{$id}\">";
        echo "<td class=\"meta\">{$id}</td>";
        echo "<td>{$nombre}</td>";
        echo "<td>{$email}</td>";
        echo "<td>{$mensaje}</td>";
        echo "<td>{$fecha}</td>";
        echo "<td class=\"actions\">";
        echo "<button class=\"btn-view\" data-id=\"{$id}\" type=\"button\">Detalles</button>";
        echo "<button class=\"btn-del\" data-id=\"{$id}\">Eliminar</button>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo '<tr><td colspan="6" class="empty">No hay mensajes.</td></tr>';
}
$mysqli->close();
?>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>
<script src="assets/js/admin.js"></script>
</body>
</html>