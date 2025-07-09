<?php
session_start();
if (!isset($_SESSION["logueado"])) {
    header("Location: login.php");
    exit;
}

$SUPABASE_URL = 'https://atzvmpqawvgwtkadsyro.supabase.co';
$SUPABASE_API_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImF0enZtcHFhd3Znd3RrYWRzeXJvIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIwMzIzNTMsImV4cCI6MjA2NzYwODM1M30.lo7kUKgVCsawEOzf5emtkDKLoDJlF9rf4xsiBT_2pXE';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $datos = [
        "nombres_completos" => $_POST["nombres_completos"] ?? '',
        "cedula" => $_POST["cedula"] ?? '',
        "correo" => $_POST["correo"] ?? '',
        "posee_seguro" => $_POST["posee_seguro"] ?? '',
        "edad" => $_POST["edad"] ?? '',
        "domicilio" => $_POST["domicilio"] ?? '',
        "contacto_emergencia" => $_POST["contacto_emergencia"] ?? '',
        "parentesco" => $_POST["parentesco"] ?? '',
        "tipo_sangre" => $_POST["tipo_sangre"] ?? '',
        "cirugias" => $_POST["cirugias"] ?? '',
        "patologias" => $_POST["patologias"] ?? '',
        "alergias" => $_POST["alergias"] ?? '',
        "historial_medico" => $_POST["historial_medico"] ?? '',
        "huella_dactilar" => $_POST["huella_dactilar"] ?? '',
        "telefono" => $_POST["telefono"] ?? '',
    ];

    $ch = curl_init("$SUPABASE_URL/rest/v1/historia_clinica");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $SUPABASE_API_KEY",
        "Authorization: Bearer $SUPABASE_API_KEY",
        "Content-Type: application/json",
        "Prefer: return=representation"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $mensaje = "✅ Paciente guardado correctamente.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Paciente</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
  <div class="container bg-white p-4 rounded shadow">
    <h3 class="mb-4 text-center">Formulario de Nuevo Paciente</h3>

    <?php if ($mensaje): ?>
      <div class="alert alert-success text-center"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="row mb-3">
        <div class="col">
          <input type="text" name="nombres_completos" class="form-control" placeholder="Nombres Completos" required>
        </div>
        <div class="col">
          <input type="text" name="cedula" class="form-control" placeholder="Cédula">
        </div>
      </div>
      <div class="row mb-3">
        <div class="col">
          <input type="text" name="telefono" class="form-control" placeholder="Teléfono">
        </div>
        <div class="col">
          <input type="text" name="edad" class="form-control" placeholder="Edad">
        </div>
      </div>
      <div class="mb-3">
        <input type="text" name="correo" class="form-control" placeholder="Correo electrónico">
      </div>
      <div class="mb-3">
        <input type="text" name="posee_seguro" class="form-control" placeholder="¿Posee seguro?">
      </div>
      <div class="mb-3">
        <input type="text" name="domicilio" class="form-control" placeholder="Domicilio">
      </div>
      <div class="row mb-3">
        <div class="col">
          <input type="text" name="contacto_emergencia" class="form-control" placeholder="Contacto emergencia">
        </div>
        <div class="col">
          <input type="text" name="parentesco" class="form-control" placeholder="Parentesco">
        </div>
      </div>
      <div class="row mb-3">
        <div class="col">
          <input type="text" name="tipo_sangre" class="form-control" placeholder="Tipo de sangre">
        </div>
        <div class="col">
          <input type="text" name="huella_dactilar" class="form-control" placeholder="Código de Huella" required>
        </div>
      </div>
      <div class="mb-3">
        <input type="text" name="cirugias" class="form-control" placeholder="Cirugías">
      </div>
      <div class="mb-3">
        <input type="text" name="patologias" class="form-control" placeholder="Patologías">
      </div>
      <div class="mb-3">
        <input type="text" name="alergias" class="form-control" placeholder="Alergias">
      </div>
      <div class="mb-3">
        <textarea name="historial_medico" class="form-control" placeholder="Historial Médico"></textarea>
      </div>
      <div class="text-center">
        <button type="submit" class="btn btn-success">Guardar Paciente</button>
        <a href="menu.php" class="btn btn-secondary ms-2">Regresar</a>
      </div>
    </form>
  </div>
</body>
</html>
