<?php
$SUPABASE_URL = 'https://atzvmpqawvgwtkadsyro.supabase.co';
$SUPABASE_API_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImF0enZtcHFhd3Znd3RrYWRzeXJvIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIwMzIzNTMsImV4cCI6MjA2NzYwODM1M30.lo7kUKgVCsawEOzf5emtkDKLoDJlF9rf4xsiBT_2pXE';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datos = [
        "nombres_completos" => $_POST["nombre"] ?? '',
        "cedula" => $_POST["cedula"] ?? '',
        "correo" => $_POST["correo"] ?? '',
        "posee_seguro" => $_POST["seguro"] ?? '',
        "edad" => $_POST["edad"] ?? '',
        "domicilio" => $_POST["domicilio"] ?? '',
        "contacto_emergencia" => $_POST["emergencia"] ?? '',
        "parentesco" => $_POST["parentesco"] ?? '',
        "tipo_sangre" => $_POST["sangre"] ?? '',
        "cirugias" => $_POST["cirugias"] ?? '',
        "patologias" => $_POST["patologias"] ?? '',
        "alergias" => $_POST["alergias"] ?? '',
        "historial_medico" => $_POST["historial"] ?? '',
        "huella_dactilar" => $_POST["codigo"] ?? '',
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

    echo "<pre>Paciente guardado:
$response</pre>";
}
?>

<form method="POST">
  <input name="nombre" placeholder="Nombre completo"><br>
  <input name="cedula" placeholder="Cédula"><br>
  <input name="correo" placeholder="Correo"><br>
  <input name="seguro" placeholder="Posee seguro"><br>
  <input name="edad" placeholder="Edad"><br>
  <input name="domicilio" placeholder="Domicilio"><br>
  <input name="emergencia" placeholder="Contacto emergencia"><br>
  <input name="parentesco" placeholder="Parentesco"><br>
  <input name="sangre" placeholder="Tipo de sangre"><br>
  <input name="cirugias" placeholder="Cirugías"><br>
  <input name="patologias" placeholder="Patologías"><br>
  <input name="alergias" placeholder="Alergias"><br>
  <input name="historial" placeholder="Historial médico"><br>
  <input name="codigo" placeholder="Código de huella"><br>
  <input name="telefono" placeholder="Teléfono"><br>
  <button type="submit">Guardar paciente</button>
</form>
