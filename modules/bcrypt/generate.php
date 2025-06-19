<?php
// Load language file
$lang = $_COOKIE['lang'] ?? 'en';
$langFile = __DIR__ . '/../../includes/languages/' . $lang . '.json';
$trans = [];

if (file_exists($langFile)) {
    $json = file_get_contents($langFile);
    $trans = json_decode($json, true);
}

// Function to get translation with fallback
function t($key, $default = '') {
    global $trans;
    $keys = explode('.', $key);
    $value = $trans;
    
    foreach ($keys as $k) {
        if (!isset($value[$k])) {
            return $default ?: $key;
        }
        $value = $value[$k];
    }
    
    return $value;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password'] ?? '');
    
    if (!empty($password)) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        echo "<strong>" . t('bcrypt.generated_hash', 'Generated Bcrypt Hash') . ":</strong><br><code>$hash</code>";
    } else {
        echo "<span style='color: red;'>" . t('bcrypt.error_enter_password', 'Please enter a password to generate a hash.') . "</span>";
    }
} else {
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= t('bcrypt.title', 'Bcrypt Generator') ?></title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 2rem;
        }
        input[type="text"], input[type="password"] {
            padding: 0.5rem;
            width: 300px;
            font-family: monospace;
        }
        button {
            padding: 0.5rem 1rem;
            margin-left: 10px;
            cursor: pointer;
        }
        .copy-btn {
            margin-left: 10px;
            cursor: pointer;
        }
        .hash-result {
            margin-top: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 4px;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <h2><?= t('bcrypt.title', 'Bcrypt Generator') ?></h2>
    <form method="POST" id="hashForm">
        <input type="password" name="password" placeholder="<?= t('bcrypt.input_placeholder', 'Enter text to hash') ?>" required>
        <button type="submit"><?= t('bcrypt.generate', 'Generate') ?></button>
    </form>
    
    <div id="hashResult" class="hash-result" style="display: none;">
        <strong><?= t('bcrypt.generated_hash', 'Generated Bcrypt Hash') ?>:</strong>
        <div style="margin: 0.5rem 0;">
            <span id="hashValue"></span>
            <button onclick="copyToClipboard()" class="copy-btn" title="<?= t('bcrypt.copy', 'Copy') ?>">
                <?= t('bcrypt.copy', 'Copy') ?>
            </button>
        </div>
        <div id="copyStatus" style="color: green; display: none;">
            <?= t('bcrypt.copied', 'Copied!') ?>
        </div>
    </div>

    <script>
    document.getElementById('hashForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('hashResult').style.display = 'block';
            document.getElementById('hashValue').innerHTML = html.match(/<code>(.*?)<\/code>/s)[1];
            document.getElementById('copyStatus').style.display = 'none';
        });
    });

    function copyToClipboard() {
        const hashValue = document.getElementById('hashValue').textContent;
        navigator.clipboard.writeText(hashValue).then(() => {
            const status = document.getElementById('copyStatus');
            status.style.display = 'block';
            setTimeout(() => {
                status.style.display = 'none';
            }, 2000);
        });
    }
    </script>
</body>
</html>
<?php
}
