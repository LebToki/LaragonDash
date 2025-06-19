
<div class="card">
  <div class="card-header">Bcrypt Password Generator</div>
  <div class="card-body">
    <input type="text" id="bcrypt-input" class="form-control mb-2" placeholder="Enter password">
    <button class="btn btn-primary" onclick="generateBcrypt()">Generate Hash</button>
    <pre class="mt-3"><code id="bcrypt-output"></code></pre>
  </div>
</div>

<div class="card mt-4">
  <div class="card-header">Verify Password</div>
  <div class="card-body">
    <input type="text" id="verify-pass" class="form-control mb-2" placeholder="Password">
    <input type="text" id="verify-hash" class="form-control mb-2" placeholder="Hash">
    <button class="btn btn-success" onclick="verifyBcrypt()">Verify</button>
    <div id="verify-result" class="mt-3"></div>
  </div>
</div>
<script>
  function generateBcrypt() {
    const password = document.getElementById('bcrypt-input').value;
    fetch('modules/bcrypt/generate.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'password=' + encodeURIComponent(password)
    }).then(res => res.text()).then(data => {
      document.getElementById('bcrypt-output').textContent = data;
    });
  }

  function verifyBcrypt() {
    const params = new URLSearchParams();
    params.append('password', document.getElementById('verify-pass').value);
    params.append('hash', document.getElementById('verify-hash').value);
    fetch('modules/bcrypt/verify.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: params.toString()
    }).then(res => res.text()).then(data => {
      document.getElementById('verify-result').textContent = data;
    });
  }
</script>
