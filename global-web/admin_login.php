<?php
session_start();
// Credenciales por defecto 
$ADMIN_USER = 'admin';
$ADMIN_PASS = 'daisuki123'; 

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = isset($_POST['username']) ? $_POST['username'] : '';
    $p = isset($_POST['password']) ? $_POST['password'] : '';
    if ($u === $ADMIN_USER && $p === $ADMIN_PASS) {
        $_SESSION['admin_logged'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $err = 'Usuario o contraseña incorrectos.';
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin — Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Shippori+Mincho:wght@400;500;600;700&family=EB+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Noto+Serif+JP:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/admin_login.css">
</head>
<body>
  <div class="container">
    <header class="site-head">
      <div class="brand"><span class="logo-jp">大好き</span><div class="brand-text">Daisuki — Admin</div></div>
      <nav aria-label="volver"><a href="index.html">Volver al sitio</a></nav>
    </header>

    <main>
      <section class="card" aria-labelledby="login-title">
        <div class="panel">
          <h2 id="login-title">Acceso administrativo</h2>
          <p class="lead">Inicia sesión para ver y gestionar los mensajes recibidos.</p>
          <?php if ($err): ?><div class="error"><?=htmlspecialchars($err)?></div><?php endif; ?>
          <form method="post" action="" class="login-form" autocomplete="off">
            <label for="username">Usuario</label>
            <input id="username" name="username" type="text" required />
            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password" required />
            <button type="submit">Ingresar</button>
            <div class="help">Credenciales de demo: <strong>admin</strong> / <strong>daisuki123</strong></div>
          </form>
        </div>

        <aside class="card-aside">
          <div>
            <div class="aside-title">Panel administrativo</div>
            <p style="margin:0;color: #e7dfd3">Accede de forma segura para revisar los mensajes de clientes. Recuerda cambiar la contraseña por defecto antes de exponer el panel.</p>
          </div>
        </aside>
      </section>
    </main>
  </div>
</body>
</html>