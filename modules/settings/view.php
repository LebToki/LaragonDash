<div class="container py-4">
	<h4 class="mb-4">🛠️ Settings</h4>
	
	<div class="row g-4">
		<div class="col-md-6">
			<label class="form-label">Theme Mode</label>
			<button class="btn btn-outline-dark w-100" data-theme-toggle>Toggle Theme</button>
		</div>
		
                <div class="col-md-6">
                        <label class="form-label">Language</label>
                        <?php
                                $langFiles = glob(__DIR__ . '/../../assets/languages/*.json');
                                $flags = [
                                        'en' => '🇬🇧',
                                        'fr' => '🇫🇷',
                                        'es' => '🇪🇸',
                                        'de' => '🇩🇪',
                                        'pt' => '🇵🇹',
                                        'id' => '🇮🇩',
                                        'tl' => '🇵🇭',
                                        'ar' => '🇸🇦'
                                ];
                                $names = [
                                        'en' => 'English',
                                        'fr' => 'Français',
                                        'es' => 'Español',
                                        'de' => 'Deutsch',
                                        'pt' => 'Português',
                                        'id' => 'Bahasa Indonesia',
                                        'tl' => 'Tagalog',
                                        'ar' => 'العربية'
                                ];
                        ?>
                        <select id="lang-select" class="form-select">
                                <?php foreach($langFiles as $file): $code = basename($file, '.json'); ?>
                                        <option value="<?= $code ?>">
                                                <?= ($flags[$code] ?? strtoupper($code)) . ' ' . ($names[$code] ?? strtoupper($code)) ?>
                                        </option>
                                <?php endforeach; ?>
                        </select>
                </div>
	</div>
</div>
