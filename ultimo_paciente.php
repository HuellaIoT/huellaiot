<?php
header('Content-Type: application/json');

$supabaseUrl = "https://atzvmpqawvgwtkadsyro.supabase.co";
$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImF0enZtcHFhd3Znd3RrYWRzeXJvIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIwMzIzNTMsImV4cCI6MjA2NzYwODM1M30.lo7kUKgVCsawEOzf5emtkDKLoDJlF9rf4xsiBT_2pXE";
$tableUltimo = "$supabaseUrl/rest/v1/ultimo_paciente?select=*";

$ch = curl_init($tableUltimo);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $apiKey",
    "Content-Type: application/json"
]);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (!empty($data)) {
    $ultimoPaciente = $data[0];
    echo json_encode([
        "success" => true,
        "paciente" => [
            "nombre" => $ultimoPaciente["nombre"],
            "edad" => $ultimoPaciente["edad"],
            "domicilio" => $paciente['domicilio'],
            "parentesco" => $paciente['parentesco'],
            "emergencia" => $paciente['contacto_emergencia'],
            "diagnostico" => $ultimoPaciente["diagnostico"]
                     // Agrega otros campos según necesites
        ]
    ]);
} else {
    echo json_encode(["success" => false, "paciente" => null]);
}
?>
