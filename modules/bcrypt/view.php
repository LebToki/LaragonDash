<div class="card shadow-sm">
	<div class="card-header bg-primary text-white">🔐 Bcrypt Password Generator</div>
	<div class="card-body">
		<div class="mb-3">
			<input type="text" id="bcrypt-input" class="form-control" placeholder="Enter password to hash">
		</div>
		<div class="d-flex align-items-center gap-2">
			<button class="btn btn-sm btn-outline-primary" onclick="generateBcrypt()">Generate Hash</button>
			<button class="btn btn-sm btn-outline-secondary" onclick="copyBcryptHash()">Copy</button>
		</div>
		<pre class="mt-3 bg-light p-2 rounded small"><code id="bcrypt-output" class="text-dark">Hash will appear here...</code></pre>
	</div>
</div>

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
