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

// Registrar la petición en la tabla peticiones_log
$log_data = [
    "codigo" => $codigo,
    "fecha" => date('c'), // Formato ISO 8601 para la fecha
    "estado" => "pendiente" // Estado inicial
];

$ch_log = curl_init();
curl_setopt($ch_log, CURLOPT_URL, "$SUPABASE_URL/rest/v1/peticiones_log");
curl_setopt($ch_log, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch_log, CURLOPT_POST, true);
curl_setopt($ch_log, CURLOPT_POSTFIELDS, json_encode($log_data));
curl_setopt($ch_log, CURLOPT_HTTPHEADER, [
    "apikey: $SUPABASE_API_KEY",
    "Authorization: Bearer $SUPABASE_API_KEY",
    "Content-Type: application/json",
    "Prefer: return=representation"
]);

$log_response = curl_exec($ch_log);
$log_http_code = curl_getinfo($ch_log, CURLINFO_HTTP_CODE);
curl_close($ch_log);

if ($log_http_code !== 201) {
    error_log("Error al registrar la petición en peticiones_log: " . $log_response);
}

// Llamar a la API REST de Supabase para consultar historia_clinica
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

// Actualizar el estado en peticiones_log según el resultado
$update_log_data = [
    "estado" => ($http_code === 200 && !empty($data)) ? "exitoso" : "fallido"
];
$ch_update = curl_init();
curl_setopt($ch_update, CURLOPT_URL, "$SUPABASE_URL/rest/v1/peticiones_log?codigo=eq.$codigo&fecha=eq." . urlencode($log_data['fecha']));
curl_setopt($ch_update, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch_update, CURLOPT_CUSTOMREQUEST, "PATCH");
curl_setopt($ch_update, CURLOPT_POSTFIELDS, json_encode($update_log_data));
curl_setopt($ch_update, CURLOPT_HTTPHEADER, [
    "apikey: $SUPABASE_API_KEY",
    "Authorization: Bearer $SUPABASE_API_KEY",
    "Content-Type: application/json",
    "Prefer: return=representation"
]);
curl_exec($ch_update);
curl_close($ch_update);

if ($http_code === 200 && !empty($data)) {
    $paciente = $data[0]; // Primer resultado

    // Actualizar la tabla ultimo_paciente
    $ultimo_paciente_data = [
        "codigo" => $codigo,
        "nombre" => $paciente['nombres_completos'],
        "cedula" => $paciente['cedula'],
        "telefono" => $paciente['telefono'],
        "edad" => $paciente['edad'],
        "domicilio" => $paciente['domicilio'],
        "parentesco" => $paciente['parentesco'],
        "emergencia" => $paciente['contacto_emergencia'],
    ];

    // Verificar si ya existe una fila en ultimo_paciente
    $ch_check = curl_init();
    curl_setopt($ch_check, CURLOPT_URL, "$SUPABASE_URL/rest/v1/ultimo_paciente");
    curl_setopt($ch_check, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch_check, CURLOPT_HTTPHEADER, [
        "apikey: $SUPABASE_API_KEY",
        "Authorization: Bearer $SUPABASE_API_KEY",
        "Content-Type: application/json"
    ]);
    $check_response = curl_exec($ch_check);
    curl_close($ch_check);
    $ultimo_paciente = json_decode($check_response, true);

    if (empty($ultimo_paciente)) {
        // Insertar nueva fila
        $ch_ultimo = curl_init();
        curl_setopt($ch_ultimo, CURLOPT_URL, "$SUPABASE_URL/rest/v1/ultimo_paciente");
        curl_setopt($ch_ultimo, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_ultimo, CURLOPT_POST, true);
        curl_setopt($ch_ultimo, CURLOPT_POSTFIELDS, json_encode($ultimo_paciente_data));
        curl_setopt($ch_ultimo, CURLOPT_HTTPHEADER, [
            "apikey: $SUPABASE_API_KEY",
            "Authorization: Bearer $SUPABASE_API_KEY",
            "Content-Type: application/json",
            "Prefer: return=representation"
        ]);
        curl_exec($ch_ultimo);
        curl_close($ch_ultimo);
    } else {
        // Actualizar la fila existente
        $id = $ultimo_paciente[0]['id'];
        $ch_ultimo = curl_init();
        curl_setopt($ch_ultimo, CURLOPT_URL, "$SUPABASE_URL/rest/v1/ultimo_paciente?id=eq.$id");
        curl_setopt($ch_ultimo, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_ultimo, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch_ultimo, CURLOPT_POSTFIELDS, json_encode($ultimo_paciente_data));
        curl_setopt($ch_ultimo, CURLOPT_HTTPHEADER, [
            "apikey: $SUPABASE_API_KEY",
            "Authorization: Bearer $SUPABASE_API_KEY",
            "Content-Type: application/json",
            "Prefer: return=representation"
        ]);
        curl_exec($ch_ultimo);
        curl_close($ch_ultimo);
    }

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
