<?php
session_start();
if (!isset($_SESSION["logueado"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Menú</title></head>
<body>
<h2>Menú Principal</h2>
<a href="agregar_huella.php"><button>Agregar Pacientes</button></a><br><br>
<a href="ver_pacientes.php"><button>Ver Pacientes</button></a>
</body>
</html>
