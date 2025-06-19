<?php
	require_once 'includes/lang.php';
	
	// Handle subpage routing
	if (isset($_GET['page']) && $_GET['page'] === 'language-editor') {
		include __DIR__ . '/language-editor.php';
		return;
	}
	
	// Detect available languages
	$langDir = __DIR__ . '/../../includes/languages/';
	$langFiles = glob($langDir . '*.json');
	
	$flags = [
		'en' => '🇬🇧', 'fr' => '🇫🇷', 'es' => '🇪🇸', 'de' => '🇩🇪',
		'pt' => '🇵🇹', 'id' => '🇮🇩', 'tl' => '🇵🇭', 'ar' => '🇸🇦'
	];
	
	$names = [
		'en' => 'English', 'fr' => 'Français', 'es' => 'Español', 'de' => 'Deutsch',
		'pt' => 'Português', 'id' => 'Bahasa Indonesia', 'tl' => 'Tagalog', 'ar' => 'العربية'
	];
?>

<div class="container py-4">
	<h4 class="mb-4"><i class="ri-settings-3-line me-1"></i> <?= t('settings.title', [], 'Settings') ?></h4>
	
	<div class="row g-4">
		<!-- Theme Mode Toggle -->
		<div class="col-md-6">
			<label class="form-label" data-i18n="settings.appearance.theme"><?= t('settings.appearance.theme', [], 'Theme') ?></label>
			<button class="btn btn-outline-dark w-100" data-theme-toggle>
				<i class="ri-contrast-line me-1"></i> <?= t('settings.appearance.toggle_button', [], 'Toggle Theme') ?>
			</button>
		</div>
		
		<!-- Language Selector -->
		<div class="col-md-6">
			<label class="form-label" data-i18n="settings.general.language"><?= t('settings.general.language', [], 'Language') ?></label>
			<select id="lang-select" class="form-select">
				<?php foreach ($langFiles as $file):
					$code = basename($file, '.json'); ?>
					<option value="<?= $code ?>">
						<?= ($flags[$code] ?? strtoupper($code)) . ' ' . ($names[$code] ?? strtoupper($code)) ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		
		<!-- Link to Language Editor -->
		<div class="col-12">
			<a href="?module=settings&page=language-editor" class="btn btn-outline-secondary w-100">
				<iconify-icon icon="mdi:file-document-edit-outline" class="me-1"></iconify-icon>
				<?= t('settings.edit_language_files', [], 'Edit Language Files') ?>
			</a>
		</div>
	</div>
</div>

<script>
	// Save selected language in localStorage
	document.getElementById('lang-select')?.addEventListener('change', function () {
		localStorage.setItem('lang', this.value);
		location.reload();
	});
	
	// Load previously selected language
	window.addEventListener('DOMContentLoaded', () => {
		const lang = localStorage.getItem('lang');
		if (lang) {
			const select = document.getElementById('lang-select');
			if (select) select.value = lang;
		}
	});
</script>
