<?php
session_start();

$usuario_valido = "admin";
$contrasena_valida = "1234";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"] ?? '';
    $contrasena = $_POST["contrasena"] ?? '';

    if ($usuario === $usuario_valido && $contrasena === $contrasena_valida) {
        $_SESSION["logueado"] = true;
        header("Location: menu.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h2>Login</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
  <input name="usuario" placeholder="Usuario"><br>
  <input name="contrasena" type="password" placeholder="Contraseña"><br>
  <button type="submit">Entrar</button>
</form>
</body>
</html>
