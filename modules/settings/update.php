<?php
require_once '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dirs = array_filter(array_map('trim', explode("\n", $_POST['ignored'] ?? '')));
    $configPath = __DIR__ . '/../../includes/config/settings.php';
    $config = include $configPath;
    $config['IgnoreDirs'] = $dirs;
    $export = "<?php\nreturn " . var_export($config, true) . ";\n";
    file_put_contents($configPath, $export);
    header('Location: ../../index.php?module=settings&saved=1');
    exit;
}
header('Location: ../../index.php?module=settings');
