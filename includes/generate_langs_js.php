<?php
	/**
	 * Dynamically generates langs.js from available PHP language files
	 * Usage: Run manually or auto-run on admin load
	 */
	
	$languageDir = __DIR__ . '/languages/';
	$outputFile  = __DIR__ . '/../assets/js/langs.js';

// Master map: Add label, flag code (ISO-3166), and text direction
	$knownLangs = [
		'en'     => ['label' => 'English', 'code' => 'gb', 'dir' => 'ltr'],
		'fr'     => ['label' => 'Français', 'code' => 'fr', 'dir' => 'ltr'],
		'es'     => ['label' => 'Español', 'code' => 'es', 'dir' => 'ltr'],
		'de'     => ['label' => 'Deutsch', 'code' => 'de', 'dir' => 'ltr'],
		'pt'     => ['label' => 'Português', 'code' => 'pt', 'dir' => 'ltr'],
		'pt-BR'  => ['label' => 'Português do Brasil', 'code' => 'br', 'dir' => 'ltr'],
		'ar'     => ['label' => 'العربية', 'code' => 'sa', 'dir' => 'rtl'],
		'ur'     => ['label' => 'اردو', 'code' => 'pk', 'dir' => 'rtl'],
		'hi'     => ['label' => 'हिन्दी', 'code' => 'in', 'dir' => 'ltr'],
		'tl'     => ['label' => 'Tagalog', 'code' => 'ph', 'dir' => 'ltr'],
		'id'     => ['label' => 'Bahasa Indonesia', 'code' => 'id', 'dir' => 'ltr'],
		'tr'     => ['label' => 'Türkçe', 'code' => 'tr', 'dir' => 'ltr'],
		'ru'     => ['label' => 'Русский', 'code' => 'ru', 'dir' => 'ltr'],
		'ja'     => ['label' => '日本語', 'code' => 'jp', 'dir' => 'ltr'],
		'ko'     => ['label' => '한국어', 'code' => 'kr', 'dir' => 'ltr'],
		'vi'     => ['label' => 'Tiếng Việt', 'code' => 'vn', 'dir' => 'ltr'],
		'zh-CN'  => ['label' => '中文（简体）', 'code' => 'cn', 'dir' => 'ltr'],
	];

// Scan available language files
	$files = scandir($languageDir);
	$available = [];
	
	foreach ($files as $file) {
		if (str_ends_with($file, '.json')) {
			$langKey = basename($file, '.json');
			if (isset($knownLangs[$langKey])) {
				$available[$langKey] = $knownLangs[$langKey];
			}
		}
	}

// Output buffer for langs.js content
	$output = "window.availableLanguages = {\n";
	foreach ($available as $key => $data) {
		$label = addslashes($data['label']);
		$flag  = $data['code'];
		$dir   = $data['dir'];
		$output .= "  \"$key\": { label: \"$label\", code: \"$flag\", dir: \"$dir\" },\n";
	}
	$output .= "};\n";

// Write to file
	file_put_contents($outputFile, $output);

// Optional: Console success message
	echo "✔ langs.js generated with " . count($available) . " languages: " . implode(', ', array_keys($available)) . PHP_EOL;
	
// Optional: Console error message
	if (count($available) === 0) {
		echo "✖ No languages found in " . $languageDir . PHP_EOL;
	}