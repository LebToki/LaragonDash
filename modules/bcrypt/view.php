<div class="card shadow-sm">
    <div class="card-header bg-primary text-white" data-i18n="bcrypt.title">Bcrypt Developer Tools</div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label" for="bcrypt-input" data-i18n="bcrypt.password">Password</label>
                <input type="text" id="bcrypt-input" class="form-control" data-i18n-placeholder="bcrypt.input_placeholder" placeholder="Password">
                <div class="mt-2 d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary" onclick="generateBcrypt()" data-i18n="bcrypt.generate">Generate</button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="copyBcryptHash()" data-i18n="bcrypt.copy">Copy</button>
                </div>
                <pre class="mt-3 bg-light p-2 rounded small"><code id="bcrypt-output" class="text-dark"></code></pre>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="verifyPassword" data-i18n="bcrypt.password">Password</label>
                <input type="text" id="verifyPassword" class="form-control" placeholder="Password">
                <label class="form-label mt-3" for="hashToVerify" data-i18n="bcrypt.hash_to_verify">Bcrypt Hash</label>
                <textarea class="form-control" id="hashToVerify" rows="3"></textarea>
                <button class="btn btn-sm btn-outline-primary mt-2" onclick="verifyBcrypt()" data-i18n="bcrypt.verify">Verify</button>
                <div id="verificationResult" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>
<script>
function generateBcrypt() {
    const password = document.getElementById('bcrypt-input').value.trim();
    const cost = 10;
    const output = document.getElementById('bcrypt-output');
    if (!password) {
        output.textContent = window.i18n?.bcrypt?.error_enter_password || 'Please enter a password';
        return;
    }
    fetch('modules/bcrypt/generate.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=generate&password=${encodeURIComponent(password)}&cost=${cost}`
    })
    .then(r => r.json())
    .then(data => {
        if(data.error) throw new Error(data.error);
        output.textContent = data.hash;
        document.getElementById('hashToVerify').value = data.hash;
    })
    .catch(e => { output.textContent = '❌ ' + e.message; });
}

function verifyBcrypt() {
    const password = document.getElementById('verifyPassword').value.trim();
    const hash = document.getElementById('hashToVerify').value.trim();
    const result = document.getElementById('verificationResult');
    if(!password || !hash) {
        result.textContent = window.i18n?.bcrypt?.error_verify_fields || 'Both fields required';
        return;
    }
    fetch('modules/bcrypt/generate.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=verify&password=${encodeURIComponent(password)}&hash=${encodeURIComponent(hash)}`
    })
    .then(r => r.json())
    .then(data => {
        if(data.error) throw new Error(data.error);
        result.textContent = data.valid ? (window.i18n?.bcrypt?.verify_success || 'Valid') : (window.i18n?.bcrypt?.verify_failed || 'Invalid');
    })
    .catch(e => { result.textContent = '❌ ' + e.message; });
}
function copyBcryptHash(){
    const hash = document.getElementById('bcrypt-output').textContent;
    if(hash){ navigator.clipboard.writeText(hash); }
}
</script>
