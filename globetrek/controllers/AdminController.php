<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../includes/functions.php';

class AdminController {

    public static function handleAddStaff() {
        require_login('admin');
        csrf_check();

        $errors = AuthController::validateRegistration($_POST);
        if (!empty($errors)) {
            $_SESSION['old'] = $_POST;
            $_SESSION['errors'] = $errors;
            redirect(BASE_URL . '/admin/manage_staff.php');
        }

        $result = User::register(
            clean($_POST['name']),
            trim($_POST['email']),
            $_POST['password'],
            clean($_POST['phone']),
            'staff'
        );

        if ($result !== true) {
            $_SESSION['old'] = $_POST;
            $_SESSION['errors'] = ['email' => $result];
            redirect(BASE_URL . '/admin/manage_staff.php');
        }

        set_flash('success', 'Staff account created.');
        redirect(BASE_URL . '/admin/manage_staff.php');
    }

    public static function handleDeleteStaff($id) {
        require_login('admin');
        User::deleteStaff($id);
        set_flash('success', 'Staff account removed.');
        redirect(BASE_URL . '/admin/manage_staff.php');
    }
}
