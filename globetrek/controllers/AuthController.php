<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../includes/functions.php';

class AuthController {

    /** Returns an array of field => error message. Empty array = valid. */
    public static function validateRegistration($post) {
        $errors = [];

        if (trim($post['name'] ?? '') === '') {
            $errors['name'] = 'Please enter your full name.';
        }

        $email = trim($post['email'] ?? '');
        if ($email === '') {
            $errors['email'] = 'Please enter your email address.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        $phone = trim($post['phone'] ?? '');
        if ($phone === '') {
            $errors['phone'] = 'Please enter a contact number.';
        } elseif (!preg_match('/^[0-9+\s-]{7,15}$/', $phone)) {
            $errors['phone'] = 'Please enter a valid phone number.';
        }

        $password = $post['password'] ?? '';
        if (strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters long.';
        }

        if (($post['confirm_password'] ?? '') !== $password) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        return $errors;
    }

    public static function handleRegister() {
        csrf_check();
        $errors = self::validateRegistration($_POST);

        if (!empty($errors)) {
            $_SESSION['old'] = $_POST;
            $_SESSION['errors'] = $errors;
            redirect(BASE_URL . '/register.php');
        }

        $result = User::register(
            clean($_POST['name']),
            trim($_POST['email']),
            $_POST['password'],
            clean($_POST['phone']),
            'customer'
        );

        if ($result !== true) {
            $_SESSION['old'] = $_POST;
            $_SESSION['errors'] = ['email' => $result];
            redirect(BASE_URL . '/register.php');
        }

        set_flash('success', 'Account created successfully. You can now log in.');
        redirect(BASE_URL . '/login.php');
    }

    public static function handleLogin() {
        csrf_check();
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $_SESSION['errors'] = ['general' => 'Please enter both email and password.'];
            $_SESSION['old'] = $_POST;
            redirect(BASE_URL . '/login.php');
        }

        $user = User::attemptLogin($email, $password);

        if (!$user) {
            $_SESSION['errors'] = ['general' => 'Incorrect email or password.'];
            $_SESSION['old'] = $_POST;
            redirect(BASE_URL . '/login.php');
        }

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        set_flash('success', 'Welcome back, ' . $user['name'] . '!');

        if ($user['role'] === 'admin') {
            redirect(BASE_URL . '/admin/dashboard.php');
        } elseif ($user['role'] === 'staff') {
            redirect(BASE_URL . '/staff/dashboard.php');
        } else {
            redirect(BASE_URL . '/index.php');
        }
    }

    public static function handleLogout() {
        $_SESSION = [];
        session_destroy();
        redirect(BASE_URL . '/login.php');
    }
}
