<?php
        $lang = $_COOKIE['lang'] ?? 'en';
        $trans = getTranslations($lang);
?>
<div class="card shadow-sm">
        <div class="card-header bg-primary text-white" data-i18n="bcrypt.title">🔐 Bcrypt Developer Tools</div>
        <div class="card-body">
                <div class="mb-3">
                        <input type="text" id="bcrypt-input" class="form-control" data-i18n="bcrypt.input_placeholder" placeholder="Enter password to hash">
                </div>
                <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-outline-primary" onclick="generateBcrypt()" data-i18n="bcrypt.generate">Generate</button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="copyBcryptHash()" data-i18n="bcrypt.copy">Copy</button>
                </div>
                <pre class="mt-3 bg-light p-2 rounded small"><code id="bcrypt-output" class="text-dark"></code></pre>
        </div>
</div>

<script>
        function generateBcrypt() {
                const password = document.getElementById('bcrypt-input').value.trim();
                const output = document.getElementById('bcrypt-output');

                if (!password) {
                        output.textContent = window.i18n?.bcrypt?.error_enter_password || '⚠️ Please enter a password.';
                        return;
                }
		
		fetch('modules/bcrypt/generate.php', {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: 'password=' + encodeURIComponent(password)
		})
                        .then(response => response.text())
                        .then(data => output.textContent = data)
                        .catch(error => output.textContent = (window.i18n?.alerts?.error_occurred || '❌ Error generating hash.'));
        }
	
        function copyBcryptHash() {
                const hash = document.getElementById('bcrypt-output').textContent;
                if (hash) {
                        navigator.clipboard.writeText(hash).then(() => {
                                showToast(window.i18n?.bcrypt?.copied || 'Copied!');
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
