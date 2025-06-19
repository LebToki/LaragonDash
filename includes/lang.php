<?php
	
	function getCurrentLang(): string
{
return $_COOKIE['lang'] ?? 'en';
}

function getTranslations(string $lang = null): array
{
$lang = $lang ?? getCurrentLang();
$path = __DIR__ . "/languages/$lang.json";
if (file_exists($path)) {
return json_decode(file_get_contents($path), true);
}
return [];
}

/**
* Translate key with optional replacements.
*/
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

if (!is_string($value)) {
return $default;
}

foreach ($replace as $search => $val) {
$value = str_replace('{' . $search . '}', $val, $value);
}

return $value;
}
