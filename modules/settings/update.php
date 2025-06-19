<?php
	require_once '../../includes/functions.php';
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$raw = $_POST['ignored'] ?? '';
		$dirs = array_filter(array_map('trim', explode("\n", $raw)), function ($d) {
			return $d !== '' && !preg_match('/[^\w\-\.\/]/', $d); // basic sanitization
		});
		
		$configPath = __DIR__ . '/../../includes/config/settings.php';
		$config = include $configPath;
		
		$config['ignored_dirs'] = $dirs;
		
		// Optional: Backup before overwrite
		@copy($configPath, $configPath . '.bak');
		
		$export = "<?php\nreturn " . var_export($config, true) . ";\n";
		file_put_contents($configPath, $export);
		
		header('Location: ../../index.php?module=settings&saved=1');
		exit;
	}
	
	header('Location: ../../index.php?module=settings');
