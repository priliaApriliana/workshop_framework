<?php
$iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.'));
foreach ($iter as $f) {
    if (strpos($f->getPathname(), 'vendor') !== false) continue;
    if ($f->isFile() && $f->getExtension() == 'php') {
        $c = file_get_contents($f);
        if (substr($c, 0, 3) === "\xEF\xBB\xBF") {
            echo 'BOM found in ' . $f->getPathname() . PHP_EOL;
            file_put_contents($f->getPathname(), substr($c, 3));
            echo "Removed BOM from " . $f->getPathname() . PHP_EOL;
        } else if (preg_match('/^\s+<\?php/', $c)) {
            echo 'Whitespace found in ' . $f->getPathname() . PHP_EOL;
        }
    }
}
echo "Done checking all files for BOM and whitespace.\n";
