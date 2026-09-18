<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$failed = false;
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
foreach ($files as $file) {
    $relative = substr($file->getPathname(), strlen($root) + 1);
    if ($file->getExtension() !== 'php' || preg_match('~^(vendor|node_modules|storage)/~', $relative)) {
        continue;
    }
    passthru(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($file->getPathname()), $code);
    $failed = $failed || $code !== 0;
}
exit($failed ? 1 : 0);
