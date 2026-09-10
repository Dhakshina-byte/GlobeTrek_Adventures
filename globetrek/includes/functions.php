<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!defined('BASE_URL')) {
    $projectRoot = realpath(__DIR__ . '/..');
    $docRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
    $base = ($docRoot && strpos($projectRoot, $docRoot) === 0)
        ? substr($projectRoot, strlen($docRoot))
        : '';
    $base = str_replace('\\', '/', $base);
    define('BASE_URL', rtrim($base, '/'));
}


function clean($value) {
    return htmlspecialchars(trim($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/** Redirect helper */
function redirect($url) {
    header("Location: $url");
    exit;
}

function set_flash($type, $message) {
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}


function get_flashes() {
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}


function is_logged_in() {
    return isset($_SESSION['user_id']);
}


function current_role() {
    return $_SESSION['role'] ?? null;
}


function require_login($roles = null) {
    if (!is_logged_in()) {
        set_flash('error', 'Please log in to continue.');
        redirect(BASE_URL . '/login.php');
    }
    if ($roles !== null) {
        $roles = (array) $roles;
        if (!in_array(current_role(), $roles, true)) {
            set_flash('error', 'You do not have permission to view that page.');
            redirect(BASE_URL . '/index.php');
        }
    }
}


function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_check() {
    $token = $_POST['csrf_token'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        set_flash('error', 'Your session expired, please try again.');
        redirect($_SERVER['HTTP_REFERER'] ?? BASE_URL . '/index.php');
    }
}


function money($amount) {
    return '$' . number_format((float) $amount, 2);
}


function old($field, $default = '') {
    $value = $_SESSION['old'][$field] ?? $default;
    return clean($value);
}

function field_error($field) {
    if (!empty($_SESSION['errors'][$field])) {
        return '<div class="field-error">' . clean($_SESSION['errors'][$field]) . '</div>';
    }
    return '';
}


function clear_old() {
    unset($_SESSION['old'], $_SESSION['errors']);
}
