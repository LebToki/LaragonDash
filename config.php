<?php
// Basic configuration for LaragonDash

// Path where HTML email files are stored
if (!defined('SENDMAIL_OUTPUT_DIR')) {
    define('SENDMAIL_OUTPUT_DIR', __DIR__ . '/emails');
}

// Laragon projects path (usually one directory up from Laragon's www folder)
$laraconfig = [
    'ProjectPath' => '..',  // Points to Laragon's www directory
    'IgnoreDirs' => ['.', '..', 'LaragonDash', 'logs', 'vendor', 'assets', '.git', '.idea'],
    'ProjectURL' => 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
];

// Additional configuration can be placed here
