<?php
require_once __DIR__ . '/../models/Package.php';
require_once __DIR__ . '/../includes/functions.php';

class PackageController {

    const MAX_IMAGE_BYTES = 2 * 1024 * 1024; // 2MB
    const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    public static function listPackages() {
        $search = clean($_GET['search'] ?? '');
        return Package::getAll($search);
    }

    public static function validate($post) {
        $errors = [];

        if (trim($post['title'] ?? '') === '') {
            $errors['title'] = 'Please enter a package title.';
        }
        if (trim($post['destination'] ?? '') === '') {
            $errors['destination'] = 'Please enter a destination.';
        }
        if (trim($post['description'] ?? '') === '') {
            $errors['description'] = 'Please enter a description.';
        }
        if (!is_numeric($post['price'] ?? '') || (float) $post['price'] <= 0) {
            $errors['price'] = 'Please enter a valid price greater than 0.';
        }
        if (!ctype_digit((string) ($post['duration_days'] ?? '')) || (int) $post['duration_days'] <= 0) {
            $errors['duration_days'] = 'Please enter a valid number of days.';
        }
        if (!isset($post['duration_nights']) || !ctype_digit((string) $post['duration_nights'])) {
            $errors['duration_nights'] = 'Please enter a valid number of nights.';
        }

        return $errors;
    }

    /** Validates the optional uploaded image. Returns error string or null. */
    public static function validateImage($file) {
        if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null; // no picture uploaded, that's fine
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return 'There was a problem uploading the picture.';
        }
        if ($file['size'] > self::MAX_IMAGE_BYTES) {
            return 'The picture must be smaller than 2MB.';
        }
        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, self::ALLOWED_TYPES, true)) {
            return 'Only JPG, PNG or WEBP pictures are allowed.';
        }
        return null;
    }

    public static function handleCreate() {
        require_login(['staff', 'admin']);
        csrf_check();

        $errors = self::validate($_POST);
        $imageError = self::validateImage($_FILES['image'] ?? null);
        if ($imageError) {
            $errors['image'] = $imageError;
        }

        if (!empty($errors)) {
            $_SESSION['old'] = $_POST;
            $_SESSION['errors'] = $errors;
            redirect(BASE_URL . '/staff/package_form.php');
        }

        $imageData = null;
        $imageType = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
            $imageType = mime_content_type($_FILES['image']['tmp_name']);
        }

        $data = [
            'title' => clean($_POST['title']),
            'destination' => clean($_POST['destination']),
            'description' => clean($_POST['description']),
            'activities' => clean($_POST['activities'] ?? ''),
            'price' => (float) $_POST['price'],
            'duration_days' => (int) $_POST['duration_days'],
            'duration_nights' => (int) $_POST['duration_nights'],
        ];

        Package::create($data, $imageData, $imageType, $_SESSION['user_id']);
        set_flash('success', 'Package added successfully.');
        redirect(BASE_URL . '/staff/manage_packages.php');
    }

    public static function handleUpdate($id) {
        require_login(['staff', 'admin']);
        csrf_check();

        $errors = self::validate($_POST);
        $imageError = self::validateImage($_FILES['image'] ?? null);
        if ($imageError) {
            $errors['image'] = $imageError;
        }

        if (!empty($errors)) {
            $_SESSION['old'] = $_POST;
            $_SESSION['errors'] = $errors;
            redirect(BASE_URL . '/staff/package_form.php?id=' . $id);
        }

        $imageData = null;
        $imageType = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
            $imageType = mime_content_type($_FILES['image']['tmp_name']);
        }

        $data = [
            'title' => clean($_POST['title']),
            'destination' => clean($_POST['destination']),
            'description' => clean($_POST['description']),
            'activities' => clean($_POST['activities'] ?? ''),
            'price' => (float) $_POST['price'],
            'duration_days' => (int) $_POST['duration_days'],
            'duration_nights' => (int) $_POST['duration_nights'],
        ];

        Package::update($id, $data, $imageData, $imageType);
        set_flash('success', 'Package updated successfully.');
        redirect(BASE_URL . '/staff/manage_packages.php');
    }

    public static function handleDelete($id) {
        require_login(['staff', 'admin']);
        Package::delete($id);
        set_flash('success', 'Package deleted.');
        redirect(BASE_URL . '/staff/manage_packages.php');
    }
}
