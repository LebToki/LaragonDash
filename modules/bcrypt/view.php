
<div class="card">
  <div class="card-header">Bcrypt Password Generator</div>
  <div class="card-body">
    <input type="text" id="bcrypt-input" class="form-control mb-2" placeholder="Enter password">
    <button class="btn btn-primary" onclick="generateBcrypt()">Generate Hash</button>
    <pre class="mt-3"><code id="bcrypt-output"></code></pre>
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
</script>
