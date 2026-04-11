<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$c = App\Models\Customer::whereNotNull('foto_blob')->latest()->first();
echo 'First call length: ' . strlen($c->foto_blob) . "\n";
echo 'Second call length: ' . strlen($c->foto_blob) . "\n";
