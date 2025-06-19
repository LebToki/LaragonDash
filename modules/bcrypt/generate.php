<?php
require_once __DIR__ . '/../../includes/lang.php';

function t($key, $default = '') {
    $lang = $_COOKIE['lang'] ?? 'en';
    $trans = getTranslations($lang);
    $keys = explode('.', $key);
    $val = $trans;
    foreach ($keys as $k) {
        if (!isset($val[$k])) return $default;
        $val = $val[$k];
    }
    return $val ?: $default;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $action = $_POST['action'] ?? 'generate';
    $resp = ['success' => false];

    try {
        if ($action === 'generate') {
            $password = $_POST['password'] ?? '';
            $cost = (int)($_POST['cost'] ?? 10);
            if ($password === '') {
                throw new Exception(t('bcrypt.error_enter_password', 'Please enter a password')); 
            }
            $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
            if ($hash === false) {
                throw new Exception(t('bcrypt.error_generating_hash', 'Error generating hash'));
            }
            $resp = ['success' => true, 'hash' => $hash, 'cost' => $cost];
        } elseif ($action === 'verify') {
            $password = $_POST['password'] ?? '';
            $hash = $_POST['hash'] ?? '';
            if ($password === '' || $hash === '') {
                throw new Exception(t('bcrypt.error_verify_fields', 'Both password and hash are required'));
            }
            $valid = password_verify($password, $hash);
            $info = $valid ? password_get_info($hash) : null;
            $resp = ['success' => true, 'valid' => $valid, 'info' => $info];
        } else {
            throw new Exception(t('bcrypt.invalid_action', 'Invalid action'));
        }
    } catch (Exception $e) {
        $resp['error'] = $e->getMessage();
    }

    echo json_encode($resp);
    exit;
}

