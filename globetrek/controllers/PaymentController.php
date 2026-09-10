<?php
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Payment.php';
require_once __DIR__ . '/../includes/functions.php';

class PaymentController {

    public static function validate($post) {
        $errors = [];

        if (trim($post['card_holder'] ?? '') === '') {
            $errors['card_holder'] = 'Please enter the name on the card.';
        }

        $cardNumber = preg_replace('/\s+/', '', $post['card_number'] ?? '');
        if (!preg_match('/^[0-9]{16}$/', $cardNumber)) {
            $errors['card_number'] = 'Please enter a valid 16-digit card number.';
        }

        if (!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $post['expiry'] ?? '')) {
            $errors['expiry'] = 'Use the MM/YY format.';
        }

        if (!preg_match('/^[0-9]{3}$/', $post['cvv'] ?? '')) {
            $errors['cvv'] = 'CVV must be 3 digits.';
        }

        return $errors;
    }

    public static function handlePay($bookingId) {
        require_login('customer');
        csrf_check();

        $booking = Booking::getById($bookingId);
        if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
            set_flash('error', 'Booking not found.');
            redirect(BASE_URL . '/my_bookings.php');
        }

        $errors = self::validate($_POST);
        if (!empty($errors)) {
            $_SESSION['old'] = $_POST;
            $_SESSION['errors'] = $errors;
            redirect(BASE_URL . '/payment.php?booking_id=' . $bookingId);
        }

        $cardNumber = preg_replace('/\s+/', '', $_POST['card_number']);

        // NOTE: this is a simulated payment for coursework purposes only.
        // A real system would use a certified payment gateway (e.g. Stripe/PayHere)
        // and would never store full card numbers.
        Payment::create(
            $bookingId,
            $booking['total_price'],
            'Card',
            clean($_POST['card_holder']),
            substr($cardNumber, -4)
        );

        Booking::markPaid($bookingId);

        set_flash('success', 'Payment successful! Your booking is confirmed.');
        redirect(BASE_URL . '/my_bookings.php');
    }
}
