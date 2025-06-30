<?php
	require_once '../../includes/functions.php';
	
	$configPath = realpath(__DIR__ . '/../../includes/config/settings.php');
	$config = is_file($configPath) ? include $configPath : [];
	
	$ignoredDirs = $config['ignored_dirs'] ?? [];
	$timezone = $config['timezone'] ?? date_default_timezone_get();
	$dbConfig = $config['db'] ?? ['host' => 'localhost', 'user' => '', 'pass' => ''];
	$prettyURL = $config['pretty_url'] ?? '.local';
	
	$timezones = timezone_identifiers_list();
	$currentUtcTime = gmdate("Y-m-d H:i:s") . ' UTC';
	$localTime = (new DateTime('now', new DateTimeZone($timezone)))->format("Y-m-d H:i:s") . " ($timezone)";
?>

<div class="container py-4">
	<h4 class="mb-4">⚙️ System Configuration</h4>
        <form method="post" action="update.php">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">
		<!-- Ignored Folders -->
		<div class="mb-3">
			<label class="form-label">Ignored Directories</label>
			<textarea name="ignored" class="form-control" rows="4"><?= htmlspecialchars(implode("\n", $ignoredDirs)) ?></textarea>
			<small class="form-text text-muted">Enter one per line. Relative paths like <code>vendor</code>, <code>logs</code>, etc.</small>
		</div>
		
		<!-- Timezone Setting -->
		<div class="mb-3">
			<label class="form-label">Default Timezone</label>
			<select name="timezone" class="form-select">
				<?php foreach ($timezones as $tz): ?>
					<option value="<?= $tz ?>" <?= $tz === $timezone ? 'selected' : '' ?>><?= $tz ?></option>
				<?php endforeach; ?>
			</select>
			<small class="text-muted d-block mt-1">UTC: <?= $currentUtcTime ?> | Local: <?= $localTime ?></small>
		</div>
		
		<!-- Pretty URL -->
		<div class="mb-3">
			<label class="form-label">Pretty URL (Development Domain)</label>
			<input type="text" name="pretty_url" class="form-control" value="<?= htmlspecialchars($prettyURL) ?>">
			<small class="text-muted">E.g. <code>.local</code>, <code>.test</code> — affects display or redirects in some dev tools.</small>
		</div>
		
		<!-- Database Check -->
		<div class="mb-3">
			<label class="form-label">Database Connection Settings</label>
			<div class="input-group mb-2">
				<span class="input-group-text">Host</span>
				<input type="text" name="db_host" class="form-control" value="<?= htmlspecialchars($dbConfig['host']) ?>">
			</div>
			<div class="input-group mb-2">
				<span class="input-group-text">User</span>
				<input type="text" name="db_user" class="form-control" value="<?= htmlspecialchars($dbConfig['user']) ?>">
			</div>
			<div class="input-group mb-2">
				<span class="input-group-text">Password</span>
				<input type="password" name="db_pass" class="form-control" value="<?= htmlspecialchars($dbConfig['pass']) ?>">
			</div>
			<small class="text-muted">Used for health check and diagnostics only. DB name and port are fixed in code.</small>
		</div>
		
		<button type="submit" class="btn btn-primary">💾 Save Settings</button>
	</form>
</div>
