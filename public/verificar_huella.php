<?php
// CONFIGURACIÓN
$SUPABASE_URL = 'https://atzvmpqawvgwtkadsyro.supabase.co';
$SUPABASE_API_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImF0enZtcHFhd3Znd3RrYWRzeXJvIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIwMzIzNTMsImV4cCI6MjA2NzYwODM1M30.lo7kUKgVCsawEOzf5emtkDKLoDJlF9rf4xsiBT_2pXE';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
    exit;
}

$codigo = $_POST['codigo'] ?? '';

if (empty($codigo)) {
    echo json_encode(["status" => "error", "message" => "Código de huella no enviado"]);
    exit;
}

// Llamar a la API REST de Supabase
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$SUPABASE_URL/rest/v1/historia_clinica?huella_dactilar=eq.$codigo");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $SUPABASE_API_KEY",
    "Authorization: Bearer $SUPABASE_API_KEY",
    "Content-Type: application/json",
    "Prefer: return=representation"
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($response === false) {
    echo json_encode(["status" => "error", "message" => curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

$data = json_decode($response, true);

if ($http_code === 200 && !empty($data)) {
    $paciente = $data[0]; // primer resultado

    echo json_encode([
        "status" => "ok",
        "message" => "Paciente encontrado",
        "nombre" => $paciente['nombres_completos'],
        "cedula" => $paciente['cedula'],
        "telefono" => $paciente['telefono'],
        "edad" => $paciente['edad'],
        "domicilio" => $paciente['domicilio'],
        "parentesco" => $paciente['parentesco'],
        "emergencia" => $paciente['contacto_emergencia'],
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Paciente no registrado"]);
}
?>
