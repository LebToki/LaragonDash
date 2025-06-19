<?php
	require_once '../../includes/functions.php';
	
	$configPath = realpath(__DIR__ . '/../../includes/config/settings.php');
	
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		header('Location: ../../index.php?module=settings');
		exit;
	}
	
	$config = is_file($configPath) ? include $configPath : [];

// Ignored Directories
	$ignoredRaw = $_POST['ignored'] ?? '';
	$config['ignored_dirs'] = array_filter(
		array_map('trim', explode("\n", $ignoredRaw)),
		fn($dir) => $dir !== '' && preg_match('/^[\w\-\.\/]+$/', $dir)
	);

// Timezone
	$config['timezone'] = in_array($_POST['timezone'], timezone_identifiers_list()) ? $_POST['timezone'] : date_default_timezone_get();

// Pretty URL
	$config['pretty_url'] = trim($_POST['pretty_url'] ?? '.local');

// Database Fields (Only safe ones)
	$config['db'] = [
		'host' => trim($_POST['db_host'] ?? 'localhost'),
		'user' => trim($_POST['db_user'] ?? ''),
		'pass' => trim($_POST['db_pass'] ?? ''),
	];

// Optional: backup
	@copy($configPath, $configPath . '.bak');

// Export
	$export = "<?php\nreturn " . var_export($config, true) . ";\n";
	file_put_contents($configPath, $export);
	
	header('Location: ../../index.php?module=settings&saved=1');
	exit;
