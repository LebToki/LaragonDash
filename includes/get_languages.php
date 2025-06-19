<?php
$files = glob(__DIR__ . '/languages/*.json');
$langs = array_map(function($f){ return basename($f, '.json'); }, $files);
header('Content-Type: application/json');
echo json_encode($langs);

