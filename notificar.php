<?php
header('Content-Type: application/json');

$codigo = $_POST['codigo'] ?? '';

if ($codigo) {
    // Configuración de Supabase
    $supabaseUrl = "https://atzvmpqawvgwtkadsyro.supabase.co";
    $apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImF0enZtcHFhd3Znd3RrYWRzeXJvIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIwMzIzNTMsImV4cCI6MjA2NzYwODM1M30.lo7kUKgVCsawEOzf5emtkDKLoDJlF9rf4xsiBT_2pXE";
    $tablePeticiones = "$supabaseUrl/rest/v1/peticiones_log";

    // Registrar la petición
    $peticionData = [
        "codigo" => $codigo,
        "fecha" => date('c'),
        "estado" => "recibido"
    ];
    $chPeticion = curl_init($tablePeticiones);
    curl_setopt($chPeticion, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chPeticion, CURLOPT_POST, true);
    curl_setopt($chPeticion, CURLOPT_HTTPHEADER, [
        "apikey: $apiKey",
        "Content-Type: application/json",
        "Prefer: return=representation"
    ]);
    curl_setopt($chPeticion, CURLOPT_POSTFIELDS, json_encode($peticionData));
    $responsePeticion = curl_exec($chPeticion);
    curl_close($chPeticion);

    // Mapear código a datos del paciente (de tu base de datos)
    $pacientes = [
        "JCC123" => ["nombre" => "Juan Pérez", "edad" => 45, "diagnostico" => "Hipertensión"],
        "ABC456" => ["nombre" => "María Gómez", "edad" => 30, "diagnostico" => "Diabetes"]
        // Agrega más pacientes según tu base de datos
    ];
    $paciente = $pacientes[$codigo] ?? ["nombre" => "Desconocido", "edad" => 0, "diagnostico" => "Sin diagnóstico"];

    // Guardar notificación (simulación, podrías usar una cola o WebSocket)
    file_put_contents('ultima_notificacion.json', json_encode([
        "success" => true,
        "paciente" => $paciente
    ]));

    echo json_encode(["success" => true, "mensaje" => "Petición registrada"]);
} else {
    echo json_encode(["success" => false, "mensaje" => "Código no proporcionado"]);
}
?>
