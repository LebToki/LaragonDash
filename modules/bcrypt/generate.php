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
            color: #495057;
        }
        
        .form-control, .form-range {
            border-radius: var(--border-radius);
            border: 1px solid #ced4da;
            padding: 0.5rem 0.75rem;
            font-size: 0.9375rem;
            transition: var(--transition);
        }
        
        .form-control:focus, .form-range:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
        }
        
        .btn {
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
        }
        
        .btn-outline-secondary {
            border-color: #ced4da;
        }
        
        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            color: #212529;
        }
        
        .result-box {
            margin-top: 1.5rem;
            padding: 1.25rem;
            background: #f8f9fa;
            border-radius: var(--border-radius);
            border-left: 4px solid var(--primary);
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .result-box h5 {
            color: var(--dark);
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .hash-display {
            background: #f1f3f5;
            padding: 1rem;
            border-radius: var(--border-radius);
            margin: 1rem 0;
            word-break: break-all;
            font-family: 'Fira Code', 'Courier New', monospace;
            font-size: 0.875rem;
            position: relative;
        }
        
        .hash-display code {
            background: transparent;
            color: #212529;
            padding: 0;
            font-size: inherit;
        }
        
        .strength-meter {
            height: 0.5rem;
            background: #e9ecef;
            border-radius: 1rem;
            margin: 1rem 0;
            overflow: hidden;
            width: 100%;
        }
        
        .strength-meter-fill {
            height: 100%;
            width: 0%;
            border-radius: 1rem;
            transition: width 0.4s ease, background-color 0.4s ease;
        }
        
        .strength-very_weak { width: 20%; background-color: var(--danger); }
        .strength-weak { width: 40%; background-color: #fd7e14; }
        .strength-moderate { width: 60%; background-color: var(--warning); }
        .strength-strong { width: 80%; background-color: #20c997; }
        .strength-very_strong { width: 100%; background-color: var(--success); }
        
        .hash-info-item {
            margin: 1rem 0;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            flex-wrap: wrap;
        }
        
        .hash-info-label {
            font-weight: 500;
            color: var(--secondary);
            min-width: 120px;
            margin-bottom: 0.25rem;
        }
        
        .hash-info-value {
            color: var(--dark);
            word-break: break-word;
            flex: 1;
            min-width: 0;
        }
        
        .strength-tips {
            margin-top: 1.5rem;
            padding: 1.25rem;
            background: #f8f9fa;
            border-radius: var(--border-radius);
            border-left: 4px solid var(--warning);
        }
        
        .strength-tips h6 {
            color: var(--dark);
            font-weight: 600;
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }
        
        .strength-tips ul {
            margin-bottom: 0;
            padding-left: 1.25rem;
        }
        
        .strength-tips li {
            margin: 0.5rem 0;
            color: var(--secondary);
        }
        
        /* Toast notifications */
        .toast {
            background: rgba(33, 37, 41, 0.95);
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .toast-header {
            border-bottom: none;
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }
        
        .toast-body {
            color: #f8f9fa;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .tool-container {
                margin: 0;
                border-radius: 0;
                box-shadow: none;
            }
            
            .tool-header {
                border-radius: 0;
            }
            
            .tab-content {
                padding: 1.25rem;
            }
            
            .nav-tabs {
                padding: 0 1rem;
                overflow-x: auto;
                flex-wrap: nowrap;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }
            
            .nav-tabs .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }
            
            .hash-info-item {
                flex-direction: column;
            }
            
            .hash-info-label {
                margin-bottom: 0.5rem;
                width: 100%;
            }
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #1a1d21;
                color: #e9ecef;
            }
            
            .tool-container, .result-box, .strength-tips {
                background-color: #2d333b;
                color: #e9ecef;
            }
            
            .form-control, .form-range, .input-group-text {
                background-color: #2d333b;
                border-color: #444c56;
                color: #e9ecef;
            }
            
            .form-control:focus, .form-range:focus {
                background-color: #2d333b;
                border-color: #539bf5;
                box-shadow: 0 0 0 0.25rem rgba(65, 132, 228, 0.25);
            }
            
            .hash-display, .hash-display code {
                background-color: #22272e;
                color: #adbac7;
            }
            
            .hash-info-label, .strength-tips li {
                color: #768390;
            }
            
            .hash-info-value, .result-box h5 {
                color: #e9ecef;
            }
            
            .nav-tabs {
                background-color: #22272e;
                border-bottom-color: #444c56;
            }
            
            .nav-tabs .nav-link {
                color: #adbac7;
            }
            
            .nav-tabs .nav-link.active {
                color: #539bf5;
            }
            
            .strength-meter {
                background-color: #373e47;
            }
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
    <!-- Toast Container -->
    <div id="toastContainer" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11"></div>
    
    <div class="tool-container">
        <div class="tool-header">
            <h1><i class="bi bi-shield-lock me-2"></i><?= htmlspecialchars(t('bcrypt.title', 'Bcrypt Tools')) ?></h1>
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
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cost" class="form-label">
                            <?= t('bcrypt.cost', 'Cost') ?>: <span id="costValue">10</span>
                            <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="<?= t('bcrypt.cost_tooltip', 'Higher values are more secure but slower to compute. Recommended: 10-12') ?>"></i>
                        </label>
                        <input type="range" class="form-range" id="cost" name="cost" min="4" max="31" value="10">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">4 (<?= t('bcrypt.fast', 'Fast') ?>)</small>
                            <small class="text-muted">10-12 (<?= t('bcrypt.recommended', 'Recommended') ?>)</small>
                            <small class="text-muted">31 (<?= t('bcrypt.secure', 'Very Secure') ?>)</small>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= t('bcrypt.generate', 'Generate') ?></button>
                </form>
                <div id="generateResult" class="result-box mt-4" style="display: none;">
                    <h5 class="mb-3">
                        <i class="bi bi-hash me-2"></i><?= t('bcrypt.generated_hash', 'Generated Hash') ?>
                        <button class="btn btn-sm btn-outline-primary float-end" onclick="copyToClipboard('generateHash')">
                            <i class="bi bi-clipboard me-1"></i><?= t('bcrypt.copy', 'Copy') ?>
                        </button>
                    </h5>
                    <div class="hash-display p-3">
                        <code id="generateHash" class="text-break"></code>
                    </div>
                    <div class="mt-2 text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        <?= t('bcrypt.hash_warning', 'This is a one-way hash. The original password cannot be retrieved.') ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="verify">
                <form id="verifyForm">
                    <div class="form-group">
                        <label for="passwordVerify" class="form-label"><?= t('bcrypt.input_placeholder', 'Enter text to verify') ?></label>
                        <div class="input-group">
                            <input type="password" id="passwordVerify" name="password" class="form-control" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleVerifyPassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hashVerify" class="form-label"><?= t('bcrypt.hash', 'Hash') ?></label>
                        <div class="input-group">
                            <input type="text" id="hashVerify" name="hash" class="form-control" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('hashVerify')" data-bs-toggle="tooltip" title="<?= t('bcrypt.copy', 'Copy') ?>">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= t('bcrypt.verify', 'Verify') ?></button>
                </form>
                <div id="verifyResult" class="alert mt-4" style="display: none;">
                    <strong><?= t('bcrypt.verify_result', 'Verify Result') ?>:</strong>
                    <div id="verifyMessage"></div>
                </div>
            </div>
            <div class="tab-pane" id="analyze">
                <form id="analyzeForm">
                    <div class="form-group">
                        <label for="hashAnalyze" class="form-label"><?= t('bcrypt.hash', 'Hash') ?></label>
                        <div class="input-group">
                            <input type="text" id="hashAnalyze" name="hash" class="form-control" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('hashAnalyze')" data-bs-toggle="tooltip" title="<?= t('bcrypt.copy', 'Copy') ?>">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= t('bcrypt.analyze', 'Analyze') ?></button>
                </form>
                <div id="analyzeResult" class="result-box mt-4" style="display: none;">
                    <h5 class="mb-3">
                        <i class="bi bi-info-circle me-2"></i><?= t('bcrypt.hash_analysis', 'Hash Analysis') ?>
                    </h5>
                    <div class="hash-info">
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
                            <div class="d-flex align-items-center">
                                <code id="analyzeSalt" class="text-break me-2"></code>
                                <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('analyzeSalt')" data-bs-toggle="tooltip" title="<?= t('bcrypt.copy', 'Copy') ?>">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                        </div>
                        <div class="hash-info-item">
                            <span class="hash-info-label"><?= t('bcrypt.hash_value', 'Hash Value') ?>:</span>
                            <div class="d-flex align-items-center">
                                <code id="analyzeHashValue" class="text-break me-2"></code>
                                <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('analyzeHashValue')" data-bs-toggle="tooltip" title="<?= t('bcrypt.copy', 'Copy') ?>">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="check_strength">
                <form id="checkStrengthForm">
                    <div class="form-group">
                        <label for="passwordStrength" class="form-label"><?= t('bcrypt.input_placeholder', 'Enter text to check strength') ?></label>
                        <div class="input-group">
                            <input type="password" id="passwordStrength" name="password" class="form-control" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleStrengthPassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted" id="passwordLength">0 <?= t('bcrypt.characters', 'characters') ?></small>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= t('bcrypt.check_strength', 'Check Strength') ?></button>
                </form>
                <div id="checkStrengthResult" class="result-box mt-4" style="display: none;">
                    <h5 class="mb-3">
                        <i class="bi bi-shield-check me-2"></i><?= t('bcrypt.password_strength', 'Password Strength') ?>
                    </h5>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span id="strengthText" class="fw-bold"></span>
                            <span id="strengthScore" class="badge bg-primary">0/5</span>
                        </div>
                        <div class="strength-meter mb-2">
                            <div class="strength-meter-fill" id="strengthMeterFill"></div>
                        </div>
                        <div id="strengthMessage" class="small text-muted"></div>
                    </div>
                    <div class="strength-tips" id="strengthTips">
                        <h6 class="fw-bold">
                            <i class="bi bi-lightbulb me-2"></i><?= t('bcrypt.tips_to_improve', 'Tips to improve your password') ?>
                        </h6>
                        <ul class="mb-0">
                            <li><?= t('bcrypt.tip_length', 'Use at least 12 characters') ?></li>
                            <li><?= t('bcrypt.tip_uppercase', 'Include uppercase letters (A-Z)') ?></li>
                            <li><?= t('bcrypt.tip_lowercase', 'Include lowercase letters (a-z)') ?></li>
                            <li><?= t('bcrypt.tip_numbers', 'Include numbers (0-9)') ?></li>
                            <li><?= t('bcrypt.tip_special', 'Include special characters (!@#$%^&*)') ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Toggle password visibility
        function setupPasswordToggle(inputId, buttonId) {
            const input = document.getElementById(inputId);
            const button = document.getElementById(buttonId);
            
            if (input && button) {
                button.addEventListener('click', function() {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    const icon = button.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('bi-eye');
                        icon.classList.toggle('bi-eye-slash');
                    }
                });
            }
        }

        // Setup password toggles
        document.addEventListener('DOMContentLoaded', function() {
            setupPasswordToggle('password', 'togglePassword');
            setupPasswordToggle('verifyPassword', 'toggleVerifyPassword');
            setupPasswordToggle('strengthPassword', 'toggleStrengthPassword');
            
            // Update cost factor display
            const costSlider = document.getElementById('cost');
            const costValue = document.getElementById('costValue');
            
            if (costSlider && costValue) {
                costValue.textContent = costSlider.value;
                costSlider.addEventListener('input', function() {
                    costValue.textContent = this.value;
                });
            }
        });

        // Form submission handlers
        async function handleFormSubmit(formElement, endpoint, successCallback) {
            const submitBtn = formElement.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            const spinner = formElement.querySelector('.spinner-border');
            
            try {
                // Show loading state
                if (spinner) spinner.classList.remove('d-none');
                submitBtn.disabled = true;
                
                const formData = new FormData(formElement);
                const response = await fetch('', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const data = await response.json();
                
                if (data.success) {
                    successCallback(data);
                } else {
                    showToast(data.message || 'An error occurred', 'danger');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred while processing your request', 'danger');
            } finally {
                // Reset button state
                if (spinner) spinner.classList.add('d-none');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        }
        
        // Show toast notification
        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toastContainer');
            if (!toastContainer) return;
            
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `toast align-items-center text-white bg-${type} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast, { autohide: true, delay: 5000 });
            bsToast.show();
            
            // Remove toast after it's hidden
            toast.addEventListener('hidden.bs.toast', function() {
                toast.remove();
            });
        }
        
        // Generate hash form
        document.getElementById('generateForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            
            await handleFormSubmit(form, '', (data) => {
                document.getElementById('generateHash').textContent = data.hash;
                document.getElementById('generateResult').style.display = 'block';
                showToast('Hash generated successfully!', 'success');
            });
        });
        
        // Verify hash form
        document.getElementById('verifyForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            
            await handleFormSubmit(form, '', (data) => {
                const resultDiv = document.getElementById('verifyResult');
                resultDiv.className = `alert alert-${data.valid ? 'success' : 'danger'}`;
                resultDiv.innerHTML = `
                    <i class="bi ${data.valid ? 'bi-check-circle-fill' : 'bi-x-circle-fill'}"></i>
                    ${data.message}
                `;
                resultDiv.style.display = 'block';
            });
        });
        
        // Analyze hash form
        document.getElementById('analyzeForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            
            await handleFormSubmit(form, '', (data) => {
                document.getElementById('analyzeAlgorithm').textContent = data.algorithm;
                document.getElementById('analyzeCost').textContent = data.cost;
                document.getElementById('analyzeSalt').textContent = data.salt;
                document.getElementById('analyzeHashValue').textContent = data.hash_value;
                document.getElementById('analyzeResult').style.display = 'block';
            });
        });
        
        // Check password strength
        document.getElementById('checkStrengthForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            
            await handleFormSubmit(form, '', (data) => {
                const strengthMeterFill = document.getElementById('strengthMeterFill');
                const strengthText = document.getElementById('strengthText');
                const strengthTips = document.getElementById('strengthTips');
                
                // Update strength meter
                strengthMeterFill.className = `strength-meter-fill strength-${data.strength}`;
                
                // Update strength text
                const strengthLabels = {
                    'very_weak': '<?= t('bcrypt.very_weak', 'Very Weak') ?>',
                    'weak': '<?= t('bcrypt.weak', 'Weak') ?>',
                    'moderate': '<?= t('bcrypt.moderate', 'Moderate') ?>',
                    'strong': '<?= t('bcrypt.strong', 'Strong') ?>',
                    'very_strong': '<?= t('bcrypt.very_strong', 'Very Strong') ?>'
                };
                
                strengthText.textContent = strengthLabels[data.strength] || '';
                
                // Update tips
                if (Array.isArray(data.tips) && data.tips.length > 0) {
                    strengthTips.innerHTML = `
                        <h6><i class="bi bi-lightbulb"></i> <?= t('bcrypt.tips_to_improve', 'Tips to improve your password') ?></h6>
                        <ul class="mb-0">
                            ${data.tips.map(tip => `<li>${tip}</li>`).join('')}
                        </ul>
                    `;
                } else {
                    strengthTips.innerHTML = '';
                }
                
                document.getElementById('checkStrengthResult').style.display = 'block';
            });
        });
        
        // Copy to clipboard function
        function copyToClipboard(id) {
            const element = document.getElementById(id);
            if (!element) return;
            
            const text = element.textContent || element.innerText;
            navigator.clipboard.writeText(text).then(() => {
                showToast('<?= t('bcrypt.copied', 'Copied to clipboard!') ?>', 'success');
            }).catch(err => {
                console.error('Failed to copy text: ', err);
                showToast('<?= t('bcrypt.copy_failed', 'Failed to copy') ?>', 'danger');
            });
        }
        
        // Real-time password strength check
        const strengthPasswordInput = document.getElementById('strengthPassword');
        if (strengthPasswordInput) {
            strengthPasswordInput.addEventListener('input', function() {
                const password = this.value;
                const lengthDisplay = document.getElementById('passwordLength');
                
                if (lengthDisplay) {
                    lengthDisplay.textContent = `${password.length} <?= t('bcrypt.characters', 'characters') ?>`;
                }
                
                // Only check strength if we have a password
                if (password.length > 0) {
                    const formData = new FormData();
                    formData.append('action', 'check_strength');
                    formData.append('password', password);
                    
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const strengthMeterFill = document.getElementById('strengthMeterFill');
                            if (strengthMeterFill) {
                                strengthMeterFill.className = `strength-meter-fill strength-${data.strength}`;
                            }
                        }
                    });
                } else {
                    // Reset strength meter if password is empty
                    const strengthMeterFill = document.getElementById('strengthMeterFill');
                    if (strengthMeterFill) {
                        strengthMeterFill.className = 'strength-meter-fill';
                        strengthMeterFill.style.width = '0%';
                    }
                }
            });
        }
    </script>
</body>
</html>
