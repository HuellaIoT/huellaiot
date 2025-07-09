<?php
$usuario = "admin";
$contrasena = "1234"; // puedes cambiar esto

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["user"] == $usuario && $_POST["pass"] == $contrasena) {
        header("Location: menu.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
<div class="container text-center">
  <div class="row justify-content-center">
    <div class="col-md-4 bg-white shadow p-4 rounded">
      <h3 class="mb-4">Ingreso al sistema</h3>
      <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
      <form method="POST">
        <div class="mb-3">
          <input type="text" name="user" class="form-control" placeholder="Usuario" required>
        </div>
        <div class="mb-3">
          <input type="password" name="pass" class="form-control" placeholder="Contraseña" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
