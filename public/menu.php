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
  <style>
    .image-container img {
      max-width: 100%; /* Asegura que no exceda el contenedor */
      height: auto; /* Mantiene la proporción */
      margin: 0 10px; /* Espaciado entre imágenes */
    }
    .custom-image {
      width: 100px; /* Ancho fijo, ajusta según necesites */
      max-height: 50px; /* Alto máximo, ajusta según necesites */
      object-fit: cover; /* Recorta o ajusta la imagen para llenar el espacio */
    }
  </style>
</head>
<body class="bg-light text-center pt-5">
  <h2 class="mb-4">Sistema de Huellas</h2>
  <div class="row justify-content-center image-container mb-4">
    <div class="col-auto">
      <img src="espoch.jpg" alt="Imagen 1" class="img-fluid custom-image">
    </div>
    <div class="col-auto">
      <img src="fie.jpg" alt="Imagen 2" class="img-fluid custom-image">
    </div>
  </div>
  <div class="d-grid gap-3 col-4 mx-auto">
    <a href="agregar_huella.php" class="btn btn-success btn-lg">Agregar Paciente</a>
    <a href="ver_pacientes.php" class="btn btn-primary btn-lg">Ver Pacientes</a>
  </div>
</body>
</html>
