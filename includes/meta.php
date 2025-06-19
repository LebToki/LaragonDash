<?php
	$title = t('app.title', [], 'LaragonDash');
	$description = t('app.description', [], 'Free local dashboard for Laragon.');
	$author = t('app.author', [], '2TInteractive');
?>

<meta charset="UTF-8">
<title><?= htmlspecialchars($title) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?= htmlspecialchars($description) ?>">
<meta name="author" content="<?= htmlspecialchars($author) ?>">
<meta name="robots" content="index, nofollow">
<meta name="keywords" content="Laragon, Dashboard, Localhost, PHP, Projects, Developer Tools, Open Source, 2TInteractive">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Open Graph -->
<meta property="og:title" content="<?= htmlspecialchars($title) ?>">
<meta property="og:description" content="<?= htmlspecialchars($description) ?>">
<meta property="og:image" content="https://2tinteractive.com/demo/laragondash/assets/images/laragondash-preview.png">
<meta property="og:url" content="https://2tinteractive.com/demo/laragondash/">
<meta property="og:type" content="website">
<meta property="og:site_name" content="LaragonDash">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($title) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($description) ?>">
<meta name="twitter:image" content="https://2tinteractive.com/demo/laragondash/assets/images/laragondash-preview.png">
