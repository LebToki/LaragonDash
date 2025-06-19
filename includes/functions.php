<?php
// Initialize empty config if not already set
if (!isset($laraconfig) || !is_array($laraconfig)) {
    $laraconfig = [];
}

// Default configuration
$defaultConfig = [
    'ProjectPath' => '..',
    'IgnoreDirs' => ['.', '..', 'LaragonDash', 'logs', 'vendor', 'assets', '.git', '.idea'],
    'ProjectURL' => 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
];

// Merge with existing config
$laraconfig = array_merge($defaultConfig, $laraconfig);

// Load additional configuration if available
if (file_exists(dirname(__DIR__) . '/config.php')) {
    require_once dirname(__DIR__) . '/config.php';
}
	
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
		
		// Debug: Show current working directory and config
		error_log('Current working directory: ' . getcwd());
		error_log('ProjectPath: ' . ($laraconfig['ProjectPath'] ?? 'not set'));
		
		$path = realpath($laraconfig['ProjectPath'] ?? '..');
		if ($path === false) {
			throw new Exception("Invalid project path: " . ($laraconfig['ProjectPath'] ?? '..'));
		}
		
		$ignored = $laraconfig['IgnoreDirs'] ?? ['.', '..', 'logs', 'vendor', 'assets'];
		$tiles = [];
		
		error_log("Scanning directory: $path");
		
		$dirs = @scandir($path);
		if ($dirs === false) {
			throw new Exception("Failed to scan directory: $path. Check if the directory exists and has proper permissions.");
		}
		
		error_log('Found ' . count($dirs) . ' items in directory');
		
		foreach ($dirs as $dir) {
			if (in_array($dir, $ignored)) continue;
			
			$fullPath = "$path/$dir";
			if (!is_dir($fullPath)) continue;
			
			if (!empty($search) && stripos($dir, $search) === false) continue;
			
			try {
				$type = detectProjectType($fullPath);
				
				$tiles[] = [
					'name' => $dir,
					'type' => $type['name'],
					'icon' => $type['icon'],
					'link' => getURLScheme() . "://$dir.local",
					'admin' => $type['admin']
				];
				
				error_log("Added project: $dir (Type: {$type['name']})");
			} catch (Exception $e) {
				error_log("Error processing directory $dir: " . $e->getMessage());
				continue;
			}
		}
		
		error_log('Total projects found: ' . count($tiles));
		return $tiles;
	}

        /**
         * Search projects and rank them by similarity to query.
         */
        function searchAndRankProjects(array $projects, string $query): array {
                if ($query === '') return $projects;
                $scored = [];
                foreach ($projects as $p) {
                        similar_text(strtolower($query), strtolower($p['name']), $percent);
                        $score = $percent;
                        if (stripos($p['name'], $query) !== false) {
                                $score += 50;
                        }
                        $scored[] = ['score' => $score, 'project' => $p];
                }
                usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);
                return array_column($scored, 'project');
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

       /**
        * List available language codes based on JSON files in assets/languages.
        */
       function getAvailableLanguages(): array {
               $files = glob(__DIR__ . '/../assets/languages/*.json');
               $codes = array_map(fn($f) => basename($f, '.json'), $files);
               sort($codes);
               return $codes;
       }

       /**
        * Convert a country code into its flag emoji.
        */
       function flagEmoji(string $cc): string {
               if (strlen($cc) !== 2) return '';
               $codepoints = [
                       0x1F1E6 + ord(strtoupper($cc[0])) - 65,
                       0x1F1E6 + ord(strtoupper($cc[1])) - 65
               ];
               return mb_convert_encoding('&#' . $codepoints[0] . ';', 'UTF-8', 'HTML-ENTITIES') .
                      mb_convert_encoding('&#' . $codepoints[1] . ';', 'UTF-8', 'HTML-ENTITIES');
       }

