<?php
// CONFIGURACIÓN
$SUPABASE_URL = 'https://atzvmpqawvgwtkadsyro.supabase.co';
$SUPABASE_API_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImF0enZtcHFhd3Znd3RrYWRzeXJvIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIwMzIzNTMsImV4cCI6MjA2NzYwODM1M30.lo7kUKgVCsawEOzf5emtkDKLoDJlF9rf4xsiBT_2pXE';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
    exit;
}

$encryptedCode = $_POST['codigo'] ?? '';

if (empty($encryptedCode)) {
    echo json_encode(["status" => "error", "message" => "Código de huella no enviado"]);
    exit;
}

// Desencriptar usando openssl_decrypt
$key = hex2bin('2b7e151628aed2a6abf7158809cf4f3c2b7e151628aed2a6abf7158809cf4f3c'); // Clave de 256 bits
$iv = hex2bin('000102030405060708090a0b0c0d0e0f'); // IV de 16 bytes
$decoded = base64_decode($encryptedCode);
$codigo = openssl_decrypt($decoded, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

// Verificar si la desencriptación fue exitosa
if ($codigo === false) {
    error_log("Error al desencriptar el código: " . openssl_error_string());
    echo json_encode(["status" => "error", "message" => "Fallo en la desencriptación"]);
    exit;
}

// Eliminar padding PKCS7 manualmente
$pad = ord($codigo[strlen($codigo) - 1]);
if ($pad <= 16) {
    $codigo = substr($codigo, 0, -$pad);
}

// Registrar la petición en la tabla peticiones_log
$log_data = [
    "codigo" => $codigo,
    "fecha" => date('c'),
    "estado" => "pendiente"
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
    $error = curl_error($ch);
    error_log("Error en consulta a historia_clinica: $error");
    echo json_encode(["status" => "error", "message" => $error]);
    curl_close($ch);
    exit;
}

curl_close($ch);

$data = json_decode($response, true);

// Actualizar el estado en peticiones_log
$update_log_data = [
    "estado" => ($http_code === 200 && !empty($data)) ? "exitoso" : "fallido"
];
$ch_update = curl_init();
curl_setopt($ch_update, CURLOPT_URL, "$SUPABASE_URL/rest/v1/peticiones_log?codigo=eq.$codigo&fecha=eq." . urlencode($log_data['fecha']));
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
    $paciente = $data[0];

    // Actualizar ultimo_paciente con todos los campos
    $ultimo_paciente_data = [
        "id" => 1,
        "codigo" => $codigo,
        "nombres_completos" => $paciente['nombres_completos'] ?? '',
        "cedula" => $paciente['cedula'] ?? '',
        "correo" => $paciente['correo'] ?? '',
        "posee_seguro" => $paciente['posee_seguro'] ?? '',
        "edad" => $paciente['edad'] ?? '',
        "domicilio" => $paciente['domicilio'] ?? '',
        "contacto_emergencia" => $paciente['contacto_emergencia'] ?? '',
        "parentesco" => $paciente['parentesco'] ?? '',
        "tipo_sangre" => $paciente['tipo_sangre'] ?? '',
        "cirugias" => $paciente['cirugias'] ?? '',
        "patologias" => $paciente['patologias'] ?? '',
        "alergias" => $paciente['alergias'] ?? '',
        "historial_medico" => $paciente['historial_medico'] ?? '',
        "huella_dactilar" => $paciente['huella_dactilar'] ?? '',
        "telefono" => $paciente['telefono'] ?? ''
    ];

    $ch_ultimo = curl_init();
    curl_setopt($ch_ultimo, CURLOPT_URL, "$SUPABASE_URL/rest/v1/ultimo_paciente?id=eq.1");
    curl_setopt($ch_ultimo, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch_ultimo, CURLOPT_POSTFIELDS, json_encode($ultimo_paciente_data));
    curl_setopt($ch_ultimo, CURLOPT_HTTPHEADER, [
        "apikey: $SUPABASE_API_KEY",
        "Authorization: Bearer $SUPABASE_API_KEY",
        "Content-Type: application/json",
        "Prefer: return=representation"
    ]);
    $ultimo_response = curl_exec($ch_ultimo);
    $ultimo_http_code = curl_getinfo($ch_ultimo, CURLINFO_HTTP_CODE);
    curl_close($ch_ultimo);

    if ($ultimo_http_code !== 200 && $ultimo_http_code !== 201) {
        error_log("Error al actualizar ultimo_paciente: Código HTTP $ultimo_http_code, Respuesta: $ultimo_response");
    }

    // Responder con todos los campos
    echo json_encode([
        "status" => "ok",
        "message" => "Paciente encontrado",
        "nombres_completos" => $paciente['nombres_completos'] ?? '',
        "cedula" => $paciente['cedula'] ?? '',
        "correo" => $paciente['correo'] ?? '',
        "posee_seguro" => $paciente['posee_seguro'] ?? '',
        "edad" => $paciente['edad'] ?? '',
        "domicilio" => $paciente['domicilio'] ?? '',
        "contacto_emergencia" => $paciente['contacto_emergencia'] ?? '',
        "parentesco" => $paciente['parentesco'] ?? '',
        "tipo_sangre" => $paciente['tipo_sangre'] ?? '',
        "cirugias" => $paciente['cirugias'] ?? '',
        "patologias" => $paciente['patologias'] ?? '',
        "alergias" => $paciente['alergias'] ?? '',
        "historial_medico" => $paciente['historial_medico'] ?? '',
        "huella_dactilar" => $paciente['huella_dactilar'] ?? '',
        "telefono" => $paciente['telefono'] ?? ''
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Paciente no registrado"]);
}
?>
