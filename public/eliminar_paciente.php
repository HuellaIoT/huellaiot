<?php
session_start();
if (!isset($_SESSION["logueado"])) {
    header("Location: login.php");
    exit;
}

$SUPABASE_URL = 'https://atzvmpqawvgwtkadsyro.supabase.co';
$SUPABASE_API_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImF0enZtcHFhd3Znd3RrYWRzeXJvIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIwMzIzNTMsImV4cCI6MjA2NzYwODM1M30.lo7kUKgVCsawEOzf5emtkDKLoDJlF9rf4xsiBT_2pXE';

$id = $_GET["id"] ?? '';

if ($id) {
    $ch = curl_init("$SUPABASE_URL/rest/v1/historia_clinica?id=eq.$id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $SUPABASE_API_KEY",
        "Authorization: Bearer $SUPABASE_API_KEY",
        "Content-Type: application/json"
    ]);
    curl_exec($ch);
    curl_close($ch);
}

header("Location: ver_pacientes.php");
exit;
