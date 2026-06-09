<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$service = app(\App\Services\FonnteNotificationService::class);
// Use reflection to test private send method
$reflection = new \ReflectionClass($service);
$method = $reflection->getMethod('send');
$method->setAccessible(true);
$response = $method->invoke($service, '087841515815', 'Tes dari Laravel Fonnte Service setelah fix .env');

echo "Response Fonnte: \n";
print_r($response);
