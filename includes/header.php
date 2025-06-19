<?php
	require_once __DIR__ . '/functions.php';
	
	$title = t('app.title', [], 'LaragonDash');
	$description = t('app.description', [], 'A dashboard to manage local development projects.');
	$author = t('app.author', [], 'Laragon');
	
	$lang = getCurrentLang();
	$dir = in_array($lang, ['ar', 'ur']) ? 'rtl' : 'ltr';
	$fontClass = match ($lang) {
		'ar' => 'font-ar',
		'ur' => 'font-ur',
		'hi' => 'font-hi',
		default => ''
	};
	$themeClass = ($theme === 'dark') ? 'theme-dark' : '';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>" dir="<?= $dir ?>">
<head>
	<meta charset="UTF-8">
	<title><?= htmlspecialchars($title) ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?= htmlspecialchars($description) ?>">
	<meta name="author" content="<?= htmlspecialchars($author) ?>">
	
	<!-- Theme Initialization -->
	<script>document.documentElement.classList.add('<?= $themeClass ?>');</script>
	
	<!-- Bootstrap and Fonts -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../assets/css/style.css">
	
	<!-- Icons & Flags -->
	<link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
	<link rel="stylesheet" href="../assets/css/flag-icons.min.css">
	<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js" defer></script>
	
	<!-- RTL Styles -->
	<style>
      body.rtl { direction: rtl; text-align: right; }
      body.font-ar { font-family: 'Tajawal', sans-serif; }
      body.font-ur { font-family: 'Noto Nastaliq Urdu', serif; }
      body.font-hi { font-family: 'Noto Sans Devanagari', sans-serif; }
	</style>
</head>
<body class="<?= "$fontClass $themeClass" ?>" data-theme="<?= $theme ?>">
