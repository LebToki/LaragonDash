<?php
// Load Settings
	$laraconfig = include __DIR__ . '/config/settings.php';
	
	/**
	 * Get the proper URL scheme (http or https) based on SSL settings.
	 */
	function getURLScheme(): string {
		global $laraconfig;
		if (!isset($laraconfig['SSLEnabled']) || !isset($laraconfig['Port'])) return 'http';
		return ($laraconfig['SSLEnabled'] == 1 && $laraconfig['Port'] != 80) ? 'https' : 'http';
	}
	
	/**
	 * Detect project type by inspecting file/folder structure.
	 */
	function detectProjectType(string $path): array {
		if (is_dir("$path/wp-admin")) {
			return ['name' => 'WordPress', 'icon' => 'logos:wordpress-icon', 'admin' => getURLScheme() . '://' . basename($path) . '.local/wp-admin'];
		}
		if (is_dir("$path/administrator")) {
			return ['name' => 'Joomla', 'icon' => 'logos:joomla', 'admin' => getURLScheme() . '://' . basename($path) . '.local/administrator'];
		}
		if (file_exists("$path/public/index.php") && file_exists("$path/.env")) {
			return ['name' => 'Laravel', 'icon' => 'logos:laravel', 'admin' => ''];
		}
		if (is_dir("$path/core") || is_dir("$path/web/core")) {
			return ['name' => 'Drupal', 'icon' => 'logos:drupal-icon', 'admin' => getURLScheme() . '://' . basename($path) . '.local/user'];
		}
		if (file_exists("$path/bin/console")) {
			return ['name' => 'Symfony', 'icon' => 'logos:symfony-icon', 'admin' => getURLScheme() . '://' . basename($path) . '.local/admin'];
		}
		if (file_exists("$path/app.py") && is_dir("$path/static")) {
			return ['name' => 'Python', 'icon' => 'logos:python-icon', 'admin' => getURLScheme() . '://' . basename($path) . '.local/Public'];
		}
		if (file_exists("$path/index.php") && is_dir("$path/application")) {
			return ['name' => 'CodeIgniter', 'icon' => 'logos:codeigniter-icon', 'admin' => ''];
		}
		return ['name' => 'Unknown', 'icon' => 'mdi:folder-outline', 'admin' => ''];
	}
	
	/**
	 * Get list of project tiles from directories.
	 */
	function getProjectTiles(string $search = ''): array {
		global $laraconfig;
		$path = $laraconfig['ProjectPath'] ?? '..';
		$ignored = $laraconfig['IgnoreDirs'] ?? ['.', '..', 'logs', 'vendor', 'assets'];
		$tiles = [];
		
		foreach (scandir($path) as $dir) {
			if (in_array($dir, $ignored)) continue;
			
			$fullPath = "$path/$dir";
			if (!is_dir($fullPath)) continue;
			
			if (!empty($search) && stripos($dir, $search) === false) continue;
			
			$type = detectProjectType($fullPath);
			
			$tiles[] = [
				'name' => $dir,
				'type' => $type['name'],
				'icon' => $type['icon'],
				'link' => getURLScheme() . "://$dir.local",
				'admin' => $type['admin']
			];
		}
		return $tiles;
	}
	
	/**
	 * Get basic system info.
	 */
	function getSystemInfo(): array {
		return [
			'PHP Version' => phpversion(),
			'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
			'Document Root' => $_SERVER['DOCUMENT_ROOT'] ?? 'N/A',
			'Date' => date('Y-m-d H:i:s'),
		];
	}
	
	/**
	 * Get system vitals (extend as needed).
	 */
	function getServerVitals(): array {
		return [
			'Uptime' => getUptime(),
			'Memory' => getMemoryUsage(),
			'Disk' => getDiskUsage(),
		];
	}
