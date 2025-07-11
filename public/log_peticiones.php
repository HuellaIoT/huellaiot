<?php
// CONFIGURACIÓN
$SUPABASE_URL = 'https://atzvmpqawvgwtkadsyro.supabase.co';
$SUPABASE_API_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImF0enZtcHFhd3Znd3RrYWRzeXJvIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIwMzIzNTMsImV4cCI6MjA2NzYwODM1M30.lo7kUKgVCsawEOzf5emtkDKLoDJlF9rf4xsiBT_2pXE';

header('Content-Type: text/html; charset=UTF-8');

// Obtener todas las peticiones de la tabla peticiones_log
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$SUPABASE_URL/rest/v1/peticiones_log?select=*&order=fecha.desc");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $SUPABASE_API_KEY",
    "Authorization: Bearer $SUPABASE_API_KEY",
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$peticiones = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log de Peticiones</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { color: #333; }
    </style>
</head>
<body>
    <h1>Log de Peticiones Recibidas</h1>
    <?php if ($http_code === 200 && !empty($peticiones)): ?>
        <table>
            <tr>
                <th>Código de Huella</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
            <?php foreach ($peticiones as $peticion): ?>
                <tr>
                    <td><?php echo htmlspecialchars($peticion['codigo']); ?></td>
                    <td><?php echo htmlspecialchars($peticion['fecha']); ?></td>
                    <td><?php echo htmlspecialchars($peticion['estado']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No se encontraron peticiones.</p>
    <?php endif; ?>
</body>
</html>