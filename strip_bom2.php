<?php
$file = 'routes/web.php';
$content = file_get_contents($file);
if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
    file_put_contents($file, substr($content, 3));
    echo "BOM removed from $file\n";
} else {
    echo "No BOM found in $file\n";
}

$file2 = 'app/Models/Customer.php';
$content2 = file_get_contents($file2);
if (substr($content2, 0, 3) === "\xEF\xBB\xBF") {
    file_put_contents($file2, substr($content2, 3));
    echo "BOM removed from $file2\n";
}

$file3 = 'app/Http/Controllers/CustomerController.php';
$content3 = file_get_contents($file3);
if (substr($content3, 0, 3) === "\xEF\xBB\xBF") {
    file_put_contents($file3, substr($content3, 3));
    echo "BOM removed from $file3\n";
}
