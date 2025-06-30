<?php
	/**
	 * Language helper: Loads JSON language file and provides translation helper `t()`
	 */
	
	function getCurrentLang(): string
	{
		return $_COOKIE['lang'] ?? 'en';
	}
	
	function getTranslations(): array
	{
		$lang = getCurrentLang();
        // Load translation from the unified languages directory
        $langFile = __DIR__ . "/languages/$lang.json";
		if (file_exists($langFile)) {
			$json = file_get_contents($langFile);
			return json_decode($json, true) ?? [];
		}
		return [];
	}

// Safe declaration of t()
	if (!function_exists('t')) {
		function t(string $key, array $replace = [], string $default = ''): string
		{
			static $trans = null;
			
			if ($trans === null) {
				$trans = getTranslations();
			}
			
			$value = $trans;
			foreach (explode('.', $key) as $part) {
				if (!isset($value[$part])) return $default ?: $key;
				$value = $value[$part];
			}
			
			if (!is_string($value)) return $default ?: $key;
			
			foreach ($replace as $search => $val) {
				$value = str_replace('{' . $search . '}', $val, $value);
			}
			
			return $value;
		}
	}
