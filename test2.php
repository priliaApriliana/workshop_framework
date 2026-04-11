<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$c = App\Models\Customer::whereNotNull('foto_blob')->latest()->first();
if ($c) {
    $raw = $c->getRawOriginal('foto_blob');
    echo 'Raw Original Type: ' . gettype($raw) . "\n";
    if (is_resource($raw)) {
        echo 'Raw length from stream: ' . strlen(stream_get_contents($raw)) . "\n";
        rewind($raw);
    } else {
        echo 'Raw Original Length: ' . strlen($raw) . "\n";
        echo 'Raw Start: ' . substr($raw, 0, 10) . "\n";
    }
}
