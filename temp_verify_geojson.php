<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$data = App\Services\CacheService::getGeoJsonData(null, true);
$first = $data['features'][0] ?? null;

echo json_encode([
    'count' => count($data['features'] ?? []),
    'sample_start' => $first['properties']['start_date'] ?? null,
    'sample_target' => $first['properties']['target_end_date'] ?? null,
], JSON_PRETTY_PRINT);
