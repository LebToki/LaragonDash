<?php
	require_once '../../includes/functions.php';
	
	$langDir = realpath(__DIR__ . '/../../includes/languages');
	$files = glob($langDir . '/*.json');
	$selected = basename($_GET['file'] ?? $_POST['file'] ?? '');
	$filePath = $selected ? realpath("$langDir/$selected") : '';
	
	if ($filePath && dirname($filePath) !== $langDir) {
		$filePath = ''; // Block directory traversal
	}
	
	$message = '';
	$content = '';
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && $filePath) {
		$json = $_POST['json'] ?? '';
		$decoded = json_decode($json, true);
		
		if (json_last_error() !== JSON_ERROR_NONE) {
			$message = '<div class="alert alert-danger">❌ Invalid JSON: ' . json_last_error_msg() . '</div>';
		} else {
			file_put_contents($filePath, json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
			$message = '<div class="alert alert-success">✅ Language file saved.</div>';
		}
	}
	
	if ($filePath && file_exists($filePath)) {
		$content = file_get_contents($filePath);
	}
?>

<div class="container py-4">
	<h4 class="mb-3"><i class="ri-translate-2"></i> <?= t('settings.language_editor_title', 'Language Editor') ?></h4>
	
	<form method="get" class="mb-3">
		<label class="form-label"><?= t('settings.select_file', 'Select Language File') ?></label>
		<select name="file" class="form-select" onchange="this.form.submit()">
			<option value=""><?= t('settings.choose_file', 'Choose...') ?></option>
			<?php foreach ($files as $f):
				$name = basename($f); ?>
				<option value="<?= $name ?>" <?= $name === $selected ? 'selected' : '' ?>>
					<?= $name ?>
				</option>
			<?php endforeach; ?>
		</select>
	</form>
	
	<?= $message ?>
	
	<?php if ($selected): ?>
		<form method="post" onsubmit="return validateJson();">
			<input type="hidden" name="file" value="<?= htmlspecialchars($selected) ?>">
			<textarea id="json-editor" name="json" rows="20" class="form-control mb-2"><?= htmlspecialchars($content) ?></textarea>
			<div class="mb-2">
				<button type="button" class="btn btn-sm btn-secondary" onclick="addGroup()">
					<i class="ri-folder-add-line"></i> <?= t('settings.add_group', 'Add Group') ?>
				</button>
				<button type="button" class="btn btn-sm btn-secondary" onclick="addKey()">
					<i class="ri-key-2-line"></i> <?= t('settings.add_key', 'Add Key') ?>
				</button>
				<button type="submit" class="btn btn-sm btn-primary">
					<i class="ri-save-line"></i> <?= t('settings.save', 'Save') ?>
				</button>
			</div>
		</form>
	<?php endif; ?>
</div>

<script>
	const editor = document.getElementById('json-editor');
	
	function addGroup() {
		if (!editor) return;
		try {
			const data = editor.value ? JSON.parse(editor.value) : {};
			const group = prompt('Group name');
			if (!group) return;
			if (!data[group]) data[group] = {};
			editor.value = JSON.stringify(data, null, 2);
		} catch (e) {
			alert('Invalid JSON in editor');
		}
	}
	
	function addKey() {
		if (!editor) return;
		try {
			const data = editor.value ? JSON.parse(editor.value) : {};
			const group = prompt('Group');
			if (!group) return;
			const key = prompt('Key');
			if (!key) return;
			const value = prompt('Value');
			if (!data[group]) data[group] = {};
			data[group][key] = value;
			editor.value = JSON.stringify(data, null, 2);
		} catch (e) {
			alert('Invalid JSON in editor');
		}
	}
	
	function validateJson() {
		if (!editor) return true;
		try {
			JSON.parse(editor.value);
			return true;
		} catch (e) {
			alert('Invalid JSON: ' + e.message);
			return false;
		}
	}
</script>
