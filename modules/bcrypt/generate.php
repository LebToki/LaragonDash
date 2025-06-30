<?php
	// BCRYPT MODULE: generate.php (Standalone UI + logic)
	
	require_once __DIR__ . '/../../includes/functions.php';
	
	// Translations
	if (!function_exists('t')) {
		function t($key, $default = '') {
			static $translations = null;
			if ($translations === null) {
				$lang = $_COOKIE['lang'] ?? 'en';
				$file = __DIR__ . "/../../includes/lang/{$lang}.json";
				if (file_exists($file)) {
					$translations = json_decode(file_get_contents($file), true) ?? [];
				} else {
					$translations = [];
				}
			}
			foreach (explode('.', $key) as $k) {
				if (!isset($translations[$k])) return $default;
				$translations = $translations[$k];
			}
			return $translations ?: $default;
		}
	}
	
	// Handle encryption/decryption requests
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		header('Content-Type: application/json');
		$action = $_POST['action'] ?? '';
		$response = ['success' => false];
		
		try {
			if ($action === 'generate') {
				$pass = $_POST['password'] ?? '';
				$cost = (int)($_POST['cost'] ?? 10);
				if (empty($pass)) throw new Exception(t('bcrypt.error_empty_password', 'Password is empty.'));
				$hash = password_hash($pass, PASSWORD_BCRYPT, ['cost' => $cost]);
				if (!$hash) throw new Exception(t('bcrypt.error_generating_hash', 'Error generating hash.'));
				$response = ['success' => true, 'hash' => $hash];
			} elseif ($action === 'verify') {
				$pass = $_POST['password'] ?? '';
				$hash = $_POST['hash'] ?? '';
				if (!$pass || !$hash) throw new Exception(t('bcrypt.error_empty_fields', 'Password and hash are required.'));
				$response = [
					'success' => true,
					'valid' => password_verify($pass, $hash),
					'info' => password_get_info($hash)
				];
			} else {
				throw new Exception(t('bcrypt.invalid_action', 'Invalid action.'));
			}
		} catch (Exception $e) {
			$response['error'] = $e->getMessage();
		}
		echo json_encode($response);
		exit;
	}
?>

<!DOCTYPE html>
<html lang="en" dir="<?= in_array($_COOKIE['lang'] ?? 'en', ['ar', 'ur']) ? 'rtl' : 'ltr' ?>">
<head>
	<meta charset="UTF-8">
	<title><?= t('bcrypt.title', 'Bcrypt Encryption & Verification') ?> - LaragonDash</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet" integrity="sha384-l4UPAMHGzl7zwogLW4nOwaU2XTk6oiM1jhCRQstZEndoIiA2I5bg6fST3wzBSRBD" crossorigin="anonymous">
	<style>
      body { padding: 2rem; background: #f5f5f5; font-family: 'Poppins', sans-serif; }
      .card { border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
      .card-header { font-weight: 600; }
      textarea, pre { font-family: monospace; }
      .output { min-height: 50px; background: #eee; padding: 1rem; border-radius: 6px; }
      .alert-small { font-size: 0.875rem; }
	</style>
</head>
<body>
<div class="container">
	<h4 class="mb-4"><i class="bi bi-shield-lock me-2"></i><?= t('bcrypt.title', 'Bcrypt Encryption & Verification') ?></h4>
	<div class="row g-4">
		<!-- Generate -->
		<div class="col-md-6">
			<div class="card">
				<div class="card-header bg-primary text-white">
					<?= t('bcrypt.generate_hash', 'Generate Hash') ?>
				</div>
				<div class="card-body">
					<div class="mb-3">
						<label class="form-label"><?= t('bcrypt.enter_password', 'Password') ?></label>
						<div class="input-group">
							<input type="password" class="form-control" id="password">
							<button class="btn btn-outline-secondary" id="togglePassword"><i class="bi bi-eye"></i></button>
						</div>
					</div>
					<div class="mb-3">
						<label class="form-label"><?= t('bcrypt.cost', 'Cost') ?>: <span id="costVal">10</span></label>
						<input type="range" class="form-range" id="cost" min="4" max="31" value="10">
					</div>
					<div class="d-flex gap-2">
						<button class="btn btn-primary w-100" id="generateBtn"><?= t('bcrypt.generate', 'Generate') ?></button>
						<button class="btn btn-outline-secondary" id="copyBtn" disabled><i class="bi bi-clipboard"></i></button>
					</div>
					<div class="output mt-3 text-break" id="hashOutput"><?= t('bcrypt.hash_placeholder', 'Your hash will appear here...') ?></div>
				</div>
			</div>
		</div>
		
		<!-- Verify -->
		<div class="col-md-6">
			<div class="card">
				<div class="card-header bg-success text-white">
					<?= t('bcrypt.verify_hash', 'Verify Hash') ?>
				</div>
				<div class="card-body">
					<div class="mb-3">
						<label class="form-label"><?= t('bcrypt.enter_password', 'Password') ?></label>
						<div class="input-group">
							<input type="password" class="form-control" id="verifyPassword">
							<button class="btn btn-outline-secondary" id="toggleVerify"><i class="bi bi-eye"></i></button>
						</div>
					</div>
					<div class="mb-3">
						<label class="form-label"><?= t('bcrypt.enter_hash', 'Bcrypt Hash') ?></label>
						<textarea class="form-control" rows="3" id="verifyHash"></textarea>
					</div>
					<button class="btn btn-success w-100" id="verifyBtn"><?= t('bcrypt.verify', 'Verify') ?></button>
					<div id="resultBox" class="alert mt-3 d-none alert-small"></div>
					<div id="infoBox" class="text-muted mt-2 small"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<script>
	function generateBcrypt() {
		const password = document.getElementById('bcrypt-input').value.trim();
		const output = document.getElementById('bcrypt-output');
		
		if (!password) {
			output.textContent = '⚠️ Please enter a password.';
			return;
		}
		
		fetch('modules/bcrypt/generate.php', {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: 'password=' + encodeURIComponent(password)
		})
			.then(response => response.text())
			.then(data => output.textContent = data)
			.catch(error => output.textContent = '❌ Error generating hash.');
	}
	
	function copyBcryptHash() {
		const hash = document.getElementById('bcrypt-output').textContent;
		if (hash && hash !== 'Hash will appear here...') {
			navigator.clipboard.writeText(hash).then(() => {
				showToast('Hash copied to clipboard.');
			});
		}
	}
	
	function showToast(msg) {
		const toast = document.getElementById('toastContent');
		toast.querySelector('.toast-body').textContent = msg;
		const bsToast = new bootstrap.Toast(toast);
		bsToast.show();
	}
</script>

<script>
	function toggleVisibility(inputId, toggleBtnId) {
		const input = document.getElementById(inputId);
		const toggle = document.getElementById(toggleBtnId);
		toggle.onclick = () => {
			input.type = input.type === 'password' ? 'text' : 'password';
			toggle.querySelector('i').classList.toggle('bi-eye');
			toggle.querySelector('i').classList.toggle('bi-eye-slash');
		};
	}
	toggleVisibility('password', 'togglePassword');
	toggleVisibility('verifyPassword', 'toggleVerify');
	
	document.getElementById('cost').oninput = function () {
		document.getElementById('costVal').textContent = this.value;
	};
	
	document.getElementById('generateBtn').onclick = async () => {
		const password = document.getElementById('password').value;
		const cost = document.getElementById('cost').value;
		const res = await fetch('', {
			method: 'POST',
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			body: `action=generate&password=${encodeURIComponent(password)}&cost=${cost}`
		});
		const json = await res.json();
		if (json.success) {
			document.getElementById('hashOutput').textContent = json.hash;
			document.getElementById('copyBtn').disabled = false;
		}
	};
	
	document.getElementById('copyBtn').onclick = () => {
		const hash = document.getElementById('hashOutput').textContent;
		navigator.clipboard.writeText(hash);
	};
	
	document.getElementById('verifyBtn').onclick = async () => {
		const password = document.getElementById('verifyPassword').value;
		const hash = document.getElementById('verifyHash').value;
		const res = await fetch('', {
			method: 'POST',
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			body: `action=verify&password=${encodeURIComponent(password)}&hash=${encodeURIComponent(hash)}`
		});
		const json = await res.json();
		const result = document.getElementById('resultBox');
		const info = document.getElementById('infoBox');
		result.classList.remove('d-none');
		if (json.valid) {
			result.className = 'alert alert-success mt-3';
			result.textContent = '✅ <?= t('bcrypt.valid_hash', 'Hash is valid') ?>';
			info.innerHTML = `<strong>Algo:</strong> ${json.info.algoName} (${json.info.algo})<br><strong>Cost:</strong> ${json.info.options.cost}`;
		} else {
			result.className = 'alert alert-danger mt-3';
			result.textContent = '❌ <?= t('bcrypt.invalid_hash', 'Invalid hash or password') ?>';
			info.innerHTML = '';
		}
	};
</script>
</body>
</html>
