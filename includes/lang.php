<?php
	
	function getCurrentLang()
	{
		return $_COOKIE['lang'] ?? 'en';
	}
	
        function getTranslations($lang = null)
        {
                $lang = $lang ?? getCurrentLang();
                $path = __DIR__ . "/languages/$lang.json";
                if (file_exists($path)) {
                        return json_decode(file_get_contents($path), true);
                }
                return [];
        }

        function t(string $key, array $replace = [], string $default = ''): string
        {
                static $translations = null;
                if ($translations === null) {
                        $translations = getTranslations();
                }

                $value = $translations;
                foreach (explode('.', $key) as $part) {
                        if (!isset($value[$part])) {
                                return $default ?: $key;
                        }
                        $value = $value[$part];
                }

                foreach ($replace as $search => $val) {
                        $value = str_replace('{' . $search . '}', $val, $value);
                }

                return $value;
        }
