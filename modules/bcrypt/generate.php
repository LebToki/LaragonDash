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

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $action = $_POST['action'] ?? '';
    $response = ['success' => false];
    
    try {
        switch ($action) {
            case 'generate':
                $password = trim($_POST['password'] ?? '');
                $cost = min(max((int)($_POST['cost'] ?? 10), 4), 31);
                
                if (empty($password)) {
                    throw new Exception(t('bcrypt.error_enter_password'));
                }
                
                $options = ['cost' => $cost];
                $hash = password_hash($password, PASSWORD_BCRYPT, $options);
                
                $response = [
                    'success' => true,
                    'hash' => $hash,
                    'message' => t('bcrypt.generated_hash')
                ];
                break;
                
            case 'verify':
                $password = trim($_POST['password'] ?? '');
                $hash = trim($_POST['hash'] ?? '');
                
                if (empty($password) || empty($hash)) {
                    throw new Exception(t('bcrypt.error_verify_fields'));
                }
                
                $isValid = password_verify($password, $hash);
                
                $response = [
                    'success' => true,
                    'valid' => $isValid,
                    'message' => $isValid ? t('bcrypt.verify_success') : t('bcrypt.verify_failed')
                ];
                break;
                
            case 'analyze':
                $hash = trim($_POST['hash'] ?? '');
                
                if (empty($hash)) {
                    throw new Exception(t('bcrypt.error_enter_hash'));
                }
                
                if (!preg_match('/^\$2[ayb]\$\d{2}\$[\d\/A-Za-z.]{53}$/', $hash)) {
                    throw new Exception(t('bcrypt.error_invalid_hash'));
                }
                
                $parts = explode('$', $hash);
                
                $response = [
                    'success' => true,
                    'algorithm' => 'bcrypt' . ($parts[1] === '2y' ? ' (2y - Blowfish)' : ' (2a - Blowfish)'),
                    'cost' => (int)$parts[2],
                    'salt' => substr($parts[3], 0, 22),
                    'hash_value' => substr($parts[3], 22),
                    'message' => t('bcrypt.hash_info')
                ];
                break;
                
            case 'check_strength':
                $password = trim($_POST['password'] ?? '');
                
                if (empty($password)) {
                    throw new Exception(t('bcrypt.error_enter_password_strength'));
                }
                
                $score = 0;
                $tips = [];
                
                // Length check
                $length = strlen($password);
                if ($length >= 12) $score += 2;
                elseif ($length >= 8) $score += 1;
                
                // Complexity checks
                if (preg_match('/[A-Z]/', $password)) $score += 1;
                if (preg_match('/[a-z]/', $password)) $score += 1;
                if (preg_match('/[0-9]/', $password)) $score += 1;
                if (preg_match('/[^A-Za-z0-9]/', $password)) $score += 1;
                
                // Common patterns to avoid
                if (preg_match('/password|12345|qwerty/i', $password)) {
                    $score = max(1, $score - 2);
                    $tips[] = t('bcrypt.strength_tip_common');
                }
                
                // Determine strength level
                if ($score <= 2) $strength = 'very_weak';
                elseif ($score <= 3) $strength = 'weak';
                elseif ($score <= 4) $strength = 'moderate';
                elseif ($score <= 5) $strength = 'strong';
                else $strength = 'very_strong';
                
                $response = [
                    'success' => true,
                    'score' => $score,
                    'strength' => $strength,
                    'tips' => array_merge([
                        t('bcrypt.strength_tip_length'),
                        t('bcrypt.strength_tip_upper'),
                        t('bcrypt.strength_tip_lower'),
                        t('bcrypt.strength_tip_number'),
                        t('bcrypt.strength_tip_special')
                    ], $tips)
                ];
                break;
        }
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
    
    echo json_encode($response);
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('bcrypt.title', 'Bcrypt Tools')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4a6ee0;
            --primary-light: #eef1fd;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
            --light: #f8f9fa;
            --dark: #343a40;
            --border: #dee2e6;
            --text-muted: #6c757d;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f5f7fb;
            color: #2d3748;
            line-height: 1.6;
        }
        
        .tool-container {
            max-width: 900px;
            margin: 2rem auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }
        
        .tool-header {
            background: var(--primary);
            color: white;
            padding: 1.5rem 2rem;
        }
        
        .tool-header h1 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 600;
        }
        
        .nav-tabs {
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            background: var(--light);
            margin: 0;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: var(--text-muted);
            font-weight: 500;
            padding: 1rem 1.5rem;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
        }
        
        .nav-tabs .nav-link:hover {
            color: var(--primary);
            background-color: rgba(74, 110, 224, 0.05);
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary);
            background: transparent;
            border-color: var(--primary);
            font-weight: 600;
        }
        
        .tab-content {
            padding: 2rem;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #4a5568;
        }
        
        .form-control, .form-select, .form-range {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid var(--border);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(74, 110, 224, 0.25);
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: #3a5bc7;
            border-color: #3a5bc7;
            transform: translateY(-1px);
        }
        
        .btn-outline-secondary {
            color: var(--text-muted);
            border-color: var(--border);
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--light);
            color: var(--dark);
        }
        
        .result-box {
            margin-top: 1.5rem;
            padding: 1.5rem;
            border-radius: 0.75rem;
            background-color: var(--light);
            border-left: 4px solid var(--primary);
        }
        
        .hash-display {
            font-family: 'Consolas', 'Monaco', monospace;
            word-break: break-all;
            background: white;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid var(--border);
            position: relative;
            padding-right: 3rem;
        }
        
        .copy-btn {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }
        
        .strength-meter {
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            margin: 1rem 0;
            overflow: hidden;
        }
        
        .strength-meter-fill {
            height: 100%;
            width: 0%;
            transition: width 0.5s ease, background-color 0.5s ease;
        }
        
        .strength-very-weak { width: 20%; background-color: #dc3545; }
        .strength-weak { width: 40%; background-color: #fd7e14; }
        .strength-moderate { width: 60%; background-color: #ffc107; }
        .strength-strong { width: 80%; background-color: #28a745; }
        .strength-very-strong { width: 100%; background-color: #20c997; }
        
        .strength-tips {
            margin-top: 1.5rem;
            padding: 1rem 1.25rem;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            border-left: 4px solid var(--warning);
        }
        
        .hash-info-item {
            display: flex;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border);
        }
        
        .hash-info-label {
            font-weight: 500;
            width: 150px;
            color: #4a5568;
        }
        
        .hash-info-value {
            flex: 1;
            font-family: 'Consolas', 'Monaco', monospace;
            word-break: break-all;
        }
        
        .loading {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 0.5rem;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .alert {
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .tool-container {
                margin: 1rem;
                border-radius: 0.75rem;
            }
            
            .nav-tabs {
                padding: 0 1rem;
                overflow-x: auto;
                flex-wrap: nowrap;
            }
            
            .nav-tabs .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
            
            .tab-content {
                padding: 1.5rem 1rem;
            }
            
            .hash-info-item {
                flex-direction: column;
                gap: 0.25rem;
            }
            
            .hash-info-label {
                width: 100%;
                margin-bottom: 0.25rem;
            }
        }
    </style>
</head>
<body>
    <div class="tool-container">
        <div class="tool-header">
            <h1><?= htmlspecialchars(t('bcrypt.title', 'Bcrypt Tools')) ?></h1>
        </div>
        <div class="nav-tabs">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#generate" data-bs-toggle="tab"><?= t('bcrypt.generate', 'Generate') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#verify" data-bs-toggle="tab"><?= t('bcrypt.verify', 'Verify') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#analyze" data-bs-toggle="tab"><?= t('bcrypt.analyze', 'Analyze') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#check_strength" data-bs-toggle="tab"><?= t('bcrypt.check_strength', 'Check Strength') ?></a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane active" id="generate">
                <form id="generateForm">
                    <div class="form-group">
                        <label for="password" class="form-label"><?= t('bcrypt.input_placeholder', 'Enter text to hash') ?></label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="cost" class="form-label"><?= t('bcrypt.cost', 'Cost') ?></label>
                        <input type="number" id="cost" name="cost" class="form-control" value="10" min="4" max="31">
                    </div>
                    <button type="submit" class="btn btn-primary"><?= t('bcrypt.generate', 'Generate') ?></button>
                </form>
                <div id="generateResult" class="result-box" style="display: none;">
                    <strong><?= t('bcrypt.generated_hash', 'Generated Bcrypt Hash') ?>:</strong>
                    <div class="hash-display">
                        <span id="generateHash"></span>
                        <button class="copy-btn" onclick="copyToClipboard('generateHash')"><?= t('bcrypt.copy', 'Copy') ?></button>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="verify">
                <form id="verifyForm">
                    <div class="form-group">
                        <label for="passwordVerify" class="form-label"><?= t('bcrypt.input_placeholder', 'Enter text to verify') ?></label>
                        <input type="password" id="passwordVerify" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="hashVerify" class="form-label"><?= t('bcrypt.hash', 'Hash') ?></label>
                        <input type="text" id="hashVerify" name="hash" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= t('bcrypt.verify', 'Verify') ?></button>
                </form>
                <div id="verifyResult" class="result-box" style="display: none;">
                    <strong><?= t('bcrypt.verify_result', 'Verify Result') ?>:</strong>
                    <div id="verifyMessage"></div>
                </div>
            </div>
            <div class="tab-pane" id="analyze">
                <form id="analyzeForm">
                    <div class="form-group">
                        <label for="hashAnalyze" class="form-label"><?= t('bcrypt.hash', 'Hash') ?></label>
                        <input type="text" id="hashAnalyze" name="hash" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= t('bcrypt.analyze', 'Analyze') ?></button>
                </form>
                <div id="analyzeResult" class="result-box" style="display: none;">
                    <strong><?= t('bcrypt.hash_info', 'Hash Info') ?>:</strong>
                    <div class="hash-info-item">
                        <span class="hash-info-label"><?= t('bcrypt.algorithm', 'Algorithm') ?>:</span>
                        <span class="hash-info-value" id="analyzeAlgorithm"></span>
                    </div>
                    <div class="hash-info-item">
                        <span class="hash-info-label"><?= t('bcrypt.cost', 'Cost') ?>:</span>
                        <span class="hash-info-value" id="analyzeCost"></span>
                    </div>
                    <div class="hash-info-item">
                        <span class="hash-info-label"><?= t('bcrypt.salt', 'Salt') ?>:</span>
                        <span class="hash-info-value" id="analyzeSalt"></span>
                    </div>
                    <div class="hash-info-item">
                        <span class="hash-info-label"><?= t('bcrypt.hash_value', 'Hash Value') ?>:</span>
                        <span class="hash-info-value" id="analyzeHashValue"></span>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="check_strength">
                <form id="checkStrengthForm">
                    <div class="form-group">
                        <label for="passwordStrength" class="form-label"><?= t('bcrypt.input_placeholder', 'Enter text to check strength') ?></label>
                        <input type="password" id="passwordStrength" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= t('bcrypt.check_strength', 'Check Strength') ?></button>
                </form>
                <div id="checkStrengthResult" class="result-box" style="display: none;">
                    <strong><?= t('bcrypt.strength', 'Strength') ?>:</strong>
                    <div class="strength-meter">
                        <div class="strength-meter-fill" id="strengthMeterFill"></div>
                    </div>
                    <div id="strengthMessage"></div>
                    <div class="strength-tips" id="strengthTips"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const generateForm = document.getElementById('generateForm');
        const verifyForm = document.getElementById('verifyForm');
        const analyzeForm = document.getElementById('analyzeForm');
        const checkStrengthForm = document.getElementById('checkStrengthForm');
        
        generateForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(generateForm);
            const response = await fetch('', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.success) {
                document.getElementById('generateHash').textContent = data.hash;
                document.getElementById('generateResult').style.display = 'block';
            } else {
                alert(data.message);
            }
        });
        
        verifyForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(verifyForm);
            const response = await fetch('', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.success) {
                document.getElementById('verifyMessage').textContent = data.message;
                document.getElementById('verifyResult').style.display = 'block';
            } else {
                alert(data.message);
            }
        });
        
        analyzeForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(analyzeForm);
            const response = await fetch('', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.success) {
                document.getElementById('analyzeAlgorithm').textContent = data.algorithm;
                document.getElementById('analyzeCost').textContent = data.cost;
                document.getElementById('analyzeSalt').textContent = data.salt;
                document.getElementById('analyzeHashValue').textContent = data.hash_value;
                document.getElementById('analyzeResult').style.display = 'block';
            } else {
                alert(data.message);
            }
        });
        
        checkStrengthForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(checkStrengthForm);
            const response = await fetch('', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.success) {
                const strengthMeterFill = document.getElementById('strengthMeterFill');
                strengthMeterFill.className = `strength-meter-fill strength-${data.strength}`;
                document.getElementById('strengthMessage').textContent = data.message;
                const strengthTips = document.getElementById('strengthTips');
                strengthTips.innerHTML = '';
                data.tips.forEach((tip) => {
                    const tipElement = document.createElement('div');
                    tipElement.textContent = tip;
                    strengthTips.appendChild(tipElement);
                });
                document.getElementById('checkStrengthResult').style.display = 'block';
            } else {
                alert(data.message);
            }
        });
        
        function copyToClipboard(id) {
            const text = document.getElementById(id).textContent;
            navigator.clipboard.writeText(text).then(() => {
                alert('Copied to clipboard!');
            });
        }
    </script>
</body>
</html>
