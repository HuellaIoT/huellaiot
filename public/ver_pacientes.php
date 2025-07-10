<?php
session_start();
if (!isset($_SESSION["logueado"])) {
    header("Location: login.php");
    exit;
}

$SUPABASE_URL = 'https://atzvmpqawvgwtkadsyro.supabase.co';
$SUPABASE_API_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImF0enZtcHFhd3Znd3RrYWRzeXJvIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIwMzIzNTMsImV4cCI6MjA2NzYwODM1M30.lo7kUKgVCsawEOzf5emtkDKLoDJlF9rf4xsiBT_2pXE'; // Reemplaza por tu clave real

$ch = curl_init("$SUPABASE_URL/rest/v1/historia_clinica?select=*");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $SUPABASE_API_KEY",
    "Authorization: Bearer $SUPABASE_API_KEY",
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
curl_close($ch);

$pacientes = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ver Pacientes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    table {
      font-size: 14px;
    }
  </style>
</head>
<body class="bg-light p-5">
  <div class="container bg-white rounded shadow p-4">
    <h3 class="mb-4 text-center">Lista de Pacientes Registrados</h3>
    <a href="menu.php" class="btn btn-secondary mb-3">Regresar al menú</a>

    <?php if ($pacientes): ?>
      <div class="table-responsive">
      <table class="table table-bordered table-hover table-striped">
        <thead class="table-dark">
          <tr>
            <th>Nombre</th>
            <th>Cédula</th>
            <th>Correo</th>
            <th>Seguro</th>
            <th>Fecha de Nacimiento</th>
            <th>Teléfono</th>
            <th>Domicilio</th>
            <th>Contacto Emergencia</th>
            <th>Parentesco</th>
            <th>Tipo de Sangre</th>
            <th>Cirugías</th>
            <th>Patologías</th>
            <th>Alergias</th>
            <th>Historial Médico</th>
            <th>Huella</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($pacientes as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p["nombres_completos"] ?? '') ?></td>
            <td><?= htmlspecialchars($p["cedula"] ?? '') ?></td>
            <td><?= htmlspecialchars($p["correo"] ?? '') ?></td>
            <td><?= htmlspecialchars($p["posee_seguro"] ?? '') ?></td>
            <td><?= htmlspecialchars($p["edad"] ?? '') ?></td>
            <td><?= htmlspecialchars($p["telefono"] ?? '') ?></td>
            <td><?= htmlspecialchars($p["domicilio"] ?? '') ?></td>
            <td><?= htmlspecialchars($p["contacto_emergencia"] ?? '') ?></td>
            <td><?= htmlspecialchars($p["parentesco"] ?? '') ?></td>
            <td><?= htmlspecialchars($p["tipo_sangre"] ?? '') ?></td>
            <td><?= htmlspecialchars($p["cirugias"] ?? '') ?></td>
            <td><?= htmlspecialchars($p["patologias"] ?? '') ?></td>
            <td><?= htmlspecialchars($p["alergias"] ?? '') ?></td>
            <td><?= htmlspecialchars($p["historial_medico"] ?? '') ?></td>
            <td><?= htmlspecialchars($p["huella_dactilar"] ?? '') ?></td>
            <td>
              <a href="editar_paciente.php?id=<?= $p["id"] ?>" class="btn btn-sm btn-warning mb-1">Editar</a><br>
              <a href="eliminar_paciente.php?id=<?= $p["id"] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este paciente?')">Eliminar</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      </div>
    <?php else: ?>
      <div class="alert alert-warning">No hay pacientes registrados.</div>
    <?php endif; ?>
  </div>
</body>
</html>
