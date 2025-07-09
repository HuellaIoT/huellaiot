<?php
session_start();
if (!isset($_SESSION["logueado"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Menú Principal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-center pt-5">
  <h2 class="mb-4">Sistema de Huellas</h2>
  <div class="d-grid gap-3 col-4 mx-auto">
    <a href="agregar_huella.php" class="btn btn-success btn-lg">Agregar Paciente</a>
    <a href="ver_pacientes.php" class="btn btn-primary btn-lg">Ver Pacientes</a>
  </div>
</body>
</html>

