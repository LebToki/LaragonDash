<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>LaragonDash – Local Project Dashboard for Developers</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="LaragonDash is a clean, local developer dashboard to organize, preview, and manage your Laragon projects effortlessly.">
	<meta name="author" content="Tarek Tarabichi from 2TInteractive">
	<meta name="robots" content="index, nofollow">
	<meta name="keywords" content="Laragon, Dashboard, Localhost, PHP, Projects, Developer Tools, Open Source, 2TInteractive">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<!-- Open Graph -->
	<meta property="og:title" content="LaragonDash – Local Project Dashboard for Developers">
	<meta property="og:description" content="Free and open-source dashboard for Laragon users to visualize local projects with system insights. Built by 2TInteractive.">
	<meta property="og:image" content="https://2tinteractive.com/demo/laragondash/assets/images/laragondash-preview.png">
	<meta property="og:url" content="https://2tinteractive.com/demo/laragondash/">
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="LaragonDash">
	
	<!-- Twitter Card -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="LaragonDash – Local Project Dashboard">
	<meta name="twitter:description" content="Free and sleek dashboard for managing local Laragon projects. No DB. Just style.">
	<meta name="twitter:image" content="https://2tinteractive.com/demo/laragondash/assets/images/laragondash-preview.png">
	
	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
	<link rel="manifest" href="../assets/favicon/site.webmanifest">
	
	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
	<!-- For Arabic -->
	<link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">
	
	<!-- For Hindi -->
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari&display=swap" rel="stylesheet">
	
	<!-- For Urdu -->
	<link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu&display=swap" rel="stylesheet">
	
<!--Language Hacks-->
	<style>
      body {
          font-family: 'Poppins', sans-serif;
          transition: font-family 0.3s ease;
      }

      body[dir="rtl"] {
          text-align: right;
      }

      body.rtl {
          direction: rtl;
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
	
	<script>
		// Force default language to English on first load if not already set
		(function () {
			const defaultLang = 'en';
			
			// Check localStorage
			if (!localStorage.getItem('lang')) {
				localStorage.setItem('lang', defaultLang);
			}
			
			// Check cookies
			if (!document.cookie.split('; ').find(row => row.startsWith('lang='))) {
				document.cookie = `lang=${defaultLang}; path=/; max-age=31536000`; // 1 year
			}
			
			// Set <html lang="en"> and dir="ltr" by default
			document.documentElement.lang = defaultLang;
			document.documentElement.setAttribute('dir', 'ltr');
		})();
	</script>
	
	
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Remixicon + Iconify -->
	<link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
	<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
	
	<!-- Custom CSS -->
	<link rel="stylesheet" href="../assets/css/style.css">
	
	<!-- Bootstrap Bundle JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body data-theme="light">
<div class="container-fluid">
	<div class="row">
