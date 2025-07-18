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
            "nombres_completos" => $ultimoPaciente["nombres_completos"] ?? '',
            "cedula" => $ultimoPaciente["cedula"] ?? '',
            "telefono" => $ultimoPaciente["telefono"] ?? '',
            "edad" => $ultimoPaciente["edad"] ?? '',
            "domicilio" => $ultimoPaciente["domicilio"] ?? '', // Corregido a $ultimoPaciente
            "parentesco" => $ultimoPaciente["parentesco"] ?? '', // Corregido
            "contacto_emergencia" => $ultimoPaciente["contacto_emergencia"] ?? '', // Corregido
            "correo" => $ultimoPaciente["correo"] ?? '',
            "posee_seguro" => $ultimoPaciente["posee_seguro"] ?? '',
            "tipo_sangre" => $ultimoPaciente["tipo_sangre"] ?? '',
            "cirugias" => $ultimoPaciente["cirugias"] ?? '',
            "patologias" => $ultimoPaciente["patologias"] ?? '',
            "alergias" => $ultimoPaciente["alergias"] ?? '',
            "historial_medico" => $ultimoPaciente["historial_medico"] ?? '',
            "huella_dactilar" => $ultimoPaciente["huella_dactilar"] ?? '',
            "diagnostico" => $ultimoPaciente["diagnostico"] ?? ''
        ]
    ]);
} else {
    echo json_encode(["success" => false, "paciente" => null]);
}
?>
