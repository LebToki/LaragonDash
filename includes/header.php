<?php
	require_once __DIR__ . '/functions.php'; // lang.php is already included
	
	// Meta data
	$title = t('app.title', [], 'LaragonDash');
	$description = t('app.description', [], 'A dashboard to manage local development projects.');
	$author = t('app.author', [], 'Laragon');
	
	// Language and theme setup
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
	<script>
		const theme = '<?= $themeClass ?>';
		if (theme.trim()) document.documentElement.classList.add(theme);
	</script>
	
	<!-- CSS: Bootstrap, Fonts, Custom -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" integrity="sha384-HA1ri+7rEK3j720NoH+23LNpqXW5vEN+bFMxcKUOY6yy4/Jz5STp9lmQdp4RNuZ0" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet" integrity="sha384-+jgDvg8/mMVgg1Wad/gcXmY3+CvLoAgRc08Q5me5yhOfUTTkXb+AVoGNli8NL5m4" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu&display=swap" rel="stylesheet" integrity="sha384-sFafEuGZrb5s/SnBn+ko9oeL5+3peFWBW/IRMhXjGo50J68zWVprjLnfKbfuSFQY" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari&display=swap" rel="stylesheet" integrity="sha384-i6006VnQG2Eb/YWmvmA31J9ubABZ7AEkFHvn+eNGS7ADiWjwbJrKRnxcL7y+vNrO" crossorigin="anonymous">
	<link rel="stylesheet" href="../assets/css/style.css">
	<link rel="stylesheet" href="../assets/css/flag-icons.min.css">
	
	
	<!--    favicon -->
	<link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon.ico">
	<link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
	<link rel="manifest" href="../assets/favicon/site.webmanifest">
	
	<!-- Icons -->
	
        <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" integrity="sha384-BqpAEqsaNwJGhbBseB3FJGMgCHCuNgcqQgOgNpxQZaY7efbx6EWfYrJFA1bGpGA9" crossorigin="anonymous">
        <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js" defer integrity="sha384-ZBvlAMcOinSpqbKp+h0PpJxrDWCO8veRjvEhIc+Wg2Um8ZUKrbyNtJChA7FhNtCF" crossorigin="anonymous"></script>
	
	<!-- RTL & Language Styles -->
	<style>
      body.rtl {
          direction: rtl;
          text-align: right;
      }

      body.font-ar {
          font-family: 'Tajawal', sans-serif;
      }

      body.font-ur {
          font-family: 'Noto Nastaliq Urdu', serif;
      }

      body.font-hi {
          font-family: 'Noto Sans Devanagari', sans-serif;
      }
	</style>
</head>
<body class="<?= "$fontClass $themeClass" ?>" data-theme="<?= $theme ?>">
