<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$c = App\Models\Customer::whereNotNull('foto_blob')->latest()->first();
if ($c) {
    $val = $c->foto_blob;
    echo 'Type: ' . gettype($val) . PHP_EOL;
    echo 'Length: ' . strlen($val) . PHP_EOL;
    echo 'First 10 chars (raw): ' . substr($val, 0, 10) . PHP_EOL;
    echo 'First 10 chars (hexed again): ' . bin2hex(substr($val, 0, 10)) . PHP_EOL;
} else {
    echo "No customer with blob found.\n";
}
