<?php
require_once __DIR__ . '/../models/Query.php';
require_once __DIR__ . '/../includes/functions.php';

class QueryController {

    public static function validate($post) {
        $errors = [];

        if (trim($post['name'] ?? '') === '') {
            $errors['name'] = 'Please enter your name.';
        }
        $email = trim($post['email'] ?? '');
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }
        if (trim($post['subject'] ?? '') === '') {
            $errors['subject'] = 'Please enter a subject.';
        }
        if (trim($post['message'] ?? '') === '') {
            $errors['message'] = 'Please enter your message.';
        }

        return $errors;
    }

    public static function handleSubmit() {
        csrf_check();
        $errors = self::validate($_POST);

        if (!empty($errors)) {
            $_SESSION['old'] = $_POST;
            $_SESSION['errors'] = $errors;
            redirect(BASE_URL . '/contact.php');
        }

        InquiryQuery::create(
            $_SESSION['user_id'] ?? null,
            clean($_POST['name']),
            trim($_POST['email']),
            clean($_POST['subject']),
            clean($_POST['message'])
        );

        set_flash('success', 'Thank you! Your query has been submitted. Our team will respond soon.');
        redirect(BASE_URL . '/contact.php');
    }

    public static function handleReply($queryId) {
        require_login(['staff', 'admin']);
        csrf_check();

        if (trim($_POST['reply'] ?? '') === '') {
            set_flash('error', 'Please write a reply before submitting.');
            redirect(BASE_URL . '/staff/manage_queries.php');
        }

        InquiryQuery::reply($queryId, clean($_POST['reply']), $_SESSION['user_id']);
        set_flash('success', 'Reply sent.');
        redirect(BASE_URL . '/staff/manage_queries.php');
    }
}
