<?php
	
	function getCurrentLang()
	{
		return $_COOKIE['lang'] ?? 'en';
	}
	
	function getTranslations($lang = null)
	{
		$lang = $lang ?? getCurrentLang();
		$path = __DIR__ . "/../assets/languages/$lang.json";
		if (file_exists($path)) {
			return json_decode(file_get_contents($path), true);
		}
		return [];
	}
