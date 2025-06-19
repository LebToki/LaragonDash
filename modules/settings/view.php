<?php
        $config = include __DIR__ . '/../../includes/config/settings.php';
        $ignored = $config['IgnoreDirs'] ?? [];
        $saved = isset($_GET['saved']);
?>
<div class="container py-4">
        <h4 class="mb-4" data-i18n="settings">🛠️ Settings</h4>

        <?php if ($saved): ?>
                <div class="alert alert-success" data-i18n="settings_saved">Settings saved.</div>
        <?php endif; ?>

        <div class="row g-4 mb-4">
                <div class="col-md-6">
                        <label class="form-label" data-i18n="theme_mode">Theme Mode</label>
                        <button class="btn btn-outline-dark w-100" data-theme-toggle>Toggle Theme</button>
                </div>

                <div class="col-md-6">
                        <label class="form-label" data-i18n="language">Language</label>
                        <select id="lang-select" class="form-select">
                                <?php
                                $available = getAvailableLanguages();
                                $flagMap = [
                                        'en' => 'gb',
                                        'fr' => 'fr',
                                        'es' => 'es',
                                        'de' => 'de',
                                        'pt' => 'pt',
                                        'id' => 'id',
                                        'tl' => 'ph',
                                        'ar' => 'sa'
                                ];
                                foreach ($available as $code):
                                        $flag = $flagMap[$code] ?? $code;
                                        echo '<option value="' . $code . '">' . flagEmoji($flag) . ' ' . strtoupper($code) . '</option>';
                                endforeach;
                                ?>
                        </select>
                </div>
        </div>

        <form method="post" action="modules/settings/update.php" class="mt-4">
                <div class="mb-3">
                        <label class="form-label" data-i18n="ignored_dirs">Ignored Directories (one per line)</label>
                        <textarea name="ignored" class="form-control" rows="5"><?= htmlspecialchars(implode("\n", $ignored)) ?></textarea>
                </div>
                <button class="btn btn-primary" type="submit" data-i18n="save">Save</button>
        </form>
</div>
