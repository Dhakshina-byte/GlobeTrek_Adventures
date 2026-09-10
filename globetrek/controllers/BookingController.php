<?php
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Package.php';
require_once __DIR__ . '/../includes/functions.php';

class BookingController {

    public static function validate($post) {
        $errors = [];

        if (empty($post['travel_date'])) {
            $errors['travel_date'] = 'Please choose a travel date.';
        } elseif (strtotime($post['travel_date']) < strtotime(date('Y-m-d'))) {
            $errors['travel_date'] = 'Travel date cannot be in the past.';
        }

        if (!ctype_digit((string) ($post['travelers'] ?? '')) || (int) $post['travelers'] < 1) {
            $errors['travelers'] = 'Please enter at least 1 traveler.';
        } elseif ((int) $post['travelers'] > 20) {
            $errors['travelers'] = 'For groups over 20, please contact us directly.';
        }

        return $errors;
    }

    public static function handleCreate($packageId) {
        require_login('customer');
        csrf_check();

        $package = Package::getById($packageId);
        if (!$package) {
            set_flash('error', 'That package could not be found.');
            redirect(BASE_URL . '/packages.php');
        }

        $errors = self::validate($_POST);
        if (!empty($errors)) {
            $_SESSION['old'] = $_POST;
            $_SESSION['errors'] = $errors;
            redirect(BASE_URL . '/book.php?id=' . $packageId);
        }

        $travelers = (int) $_POST['travelers'];
        $total = $travelers * (float) $package['price'];

        $bookingId = Booking::create(
            $_SESSION['user_id'],
            $packageId,
            $_POST['travel_date'],
            $travelers,
            clean($_POST['special_requests'] ?? ''),
            $total
        );

        redirect(BASE_URL . '/payment.php?booking_id=' . $bookingId);
    }

    public static function handleStatusChange($bookingId, $status) {
        require_login(['staff', 'admin']);
        csrf_check();
        $allowed = ['pending', 'confirmed', 'cancelled'];
        if (in_array($status, $allowed, true)) {
            Booking::updateStatus($bookingId, $status);
            set_flash('success', 'Booking status updated.');
        }
        redirect(BASE_URL . '/staff/manage_bookings.php');
    }
}
