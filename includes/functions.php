<?php

        // Ensure sessions are available for features like CSRF protection
        if (session_status() === PHP_SESSION_NONE) {
                session_start();
        }

        //------------------------------------------
	// At the top of header.php or index.php during development only
//	if (file_exists(__DIR__ . '/generate_langs_js.php')) {
//		include_once __DIR__ . '/generate_langs_js.php';
//	}
	//------------------------------------------
	
	/**
	 * Main helper functions for LaragonDash
	 */
	$laraconfig = include __DIR__ . '/config/settings.php';
	require_once __DIR__ . '/lang.php';

// Detect theme from cookie or URL
$theme = $_COOKIE['theme'] ?? $_GET['theme'] ?? 'light';

        /**
         * CSRF token helpers
         */
        function getCsrfToken(): string
        {
                if (empty($_SESSION['csrf_token'])) {
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }
                return $_SESSION['csrf_token'];
        }

        function verifyCsrfToken(?string $token): bool
        {
                return isset($_SESSION['csrf_token'], $token) && hash_equals($_SESSION['csrf_token'], $token);
        }
	
	/**
	 * Determine protocol scheme
	 */
	function getURLScheme(): string
	{
		global $laraconfig;
		if (!isset($laraconfig['SSLEnabled']) || !isset($laraconfig['Port'])) return 'http';
		return ($laraconfig['SSLEnabled'] == 1 && $laraconfig['Port'] != 80) ? 'https' : 'http';
	}
	
	/**
	 * Detect project platform by folder structure
	 */
	function detectProjectType(string $path): array
	{
		$domain = basename($path) . '.local';
		$scheme = getURLScheme();
		
		if (is_dir("$path/wp-admin")) {
			return ['name' => 'WordPress', 'icon' => 'logos:wordpress-icon', 'admin' => "$scheme://$domain/wp-admin"];
		}
		if (is_dir("$path/administrator")) {
			return ['name' => 'Joomla', 'icon' => 'logos:joomla', 'admin' => "$scheme://$domain/administrator"];
		}
		if (file_exists("$path/public/index.php") && file_exists("$path/.env")) {
			return ['name' => 'Laravel', 'icon' => 'logos:laravel', 'admin' => ''];
		}
		if (is_dir("$path/core") || is_dir("$path/web/core")) {
			return ['name' => 'Drupal', 'icon' => 'logos:drupal-icon', 'admin' => "$scheme://$domain/user"];
		}
		if (file_exists("$path/bin/console")) {
			return ['name' => 'Symfony', 'icon' => 'logos:symfony-icon', 'admin' => "$scheme://$domain/admin"];
		}
		if (file_exists("$path/app.py") && is_dir("$path/static")) {
			return ['name' => 'Python', 'icon' => 'logos:python-icon', 'admin' => "$scheme://$domain/Public"];
		}
		if (file_exists("$path/index.php") && is_dir("$path/application")) {
			return ['name' => 'CodeIgniter', 'icon' => 'logos:codeigniter-icon', 'admin' => ''];
		}
		
		return ['name' => 'Unknown', 'icon' => 'mdi:folder-outline', 'admin' => ''];
	}
	
        /**
         * Load project tiles from cache or regenerate them.
         */
        function getProjectTiles(string $search = ''): array
        {
                global $laraconfig;

                $basePath  = $laraconfig['ProjectPath'] ?? '..';
                $ignored   = $laraconfig['IgnoreDirs'] ?? ['.', '..', 'logs', 'vendor', 'assets'];
                $cacheDir  = dirname(__DIR__) . '/cache';
                $cacheFile = "$cacheDir/projects.json";
                $ttl       = 300; // seconds

                $tiles = [];

                if (file_exists($cacheFile)) {
                        $cacheTime = filemtime($cacheFile);
                        $dirTime   = filemtime($basePath);

                        if ((time() - $cacheTime) < $ttl && $dirTime < $cacheTime) {
                                $data = json_decode(file_get_contents($cacheFile), true);
                                if (is_array($data)) {
                                        $tiles = $data;
                                }
                        }
                }

                if (empty($tiles)) {
                        foreach (scandir($basePath) as $dir) {
                                if (in_array($dir, $ignored)) continue;

                                $fullPath = "$basePath/$dir";
                                if (!is_dir($fullPath)) continue;

                                $type = detectProjectType($fullPath);

                                $tiles[] = [
                                        'name'  => $dir,
                                        'type'  => $type['name'],
                                        'icon'  => $type['icon'],
                                        'link'  => getURLScheme() . "://$dir.local",
                                        'admin' => $type['admin']
                                ];
                        }

                        if (!is_dir($cacheDir)) {
                                @mkdir($cacheDir, 0777, true);
                        }
                        file_put_contents($cacheFile, json_encode($tiles));
                }

                if ($search) {
                        $tiles = array_filter($tiles, function ($tile) use ($search) {
                                return stripos($tile['name'], $search) !== false;
                        });
                        $tiles = array_values($tiles);
                }

                return $tiles;
        }
	
	/**
	 * Basic server info
	 */
	function getSystemInfo(): array
	{
		return [
			'PHP Version' => phpversion(),
			'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
			'Document Root' => $_SERVER['DOCUMENT_ROOT'] ?? 'N/A',
			'Date' => date('Y-m-d H:i:s')
		];
	}
	
	/**
	 * Vitals (dummy placeholders if not implemented)
	 */
	function getServerVitals(): array
	{
		return [
			'Uptime' => getUptime(),
			'Memory' => getMemoryUsage(),
			'Disk' => getDiskUsage()
		];
	}
