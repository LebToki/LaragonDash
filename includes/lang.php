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

/**
 * Retrieve a translation value using dot notation.
 */
function t(string $key, string $default = ''): string
{
        static $cache = null;
        if ($cache === null) {
                $cache = getTranslations();
        }

        $parts = explode('.', $key);
        $value = $cache;
        foreach ($parts as $part) {
                if (!is_array($value) || !array_key_exists($part, $value)) {
                        return $default;
                }
                $value = $value[$part];
        }

        return is_string($value) ? $value : $default;
}
