<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/models/Booking.php';
require_once __DIR__ . '/controllers/PaymentController.php';

require_login('customer');

$bookingId = (int) ($_GET['booking_id'] ?? 0);
$booking = Booking::getById($bookingId);

if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
    set_flash('error', 'Booking not found.');
    redirect(BASE_URL . '/my_bookings.php');
}

if ($booking['payment_status'] === 'paid') {
    set_flash('success', 'This booking has already been paid.');
    redirect(BASE_URL . '/my_bookings.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    PaymentController::handlePay($bookingId);
}

$pageTitle = 'Payment';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <div class="form-card">
    <h2>Secure payment</h2>
    <p class="text-muted">
      <?php echo clean($booking['title']); ?> — <?php echo (int) $booking['travelers']; ?> traveler(s)<br>
      Total due: <strong><?php echo money($booking['total_price']); ?></strong>
    </p>
    <p class="text-muted" style="font-size:.82rem">
      This is a simulated checkout for coursework purposes — no real transaction is made and card details are not stored in full.
    </p>

    <form id="paymentForm" method="POST" action="<?php echo BASE_URL; ?>/payment.php?booking_id=<?php echo $bookingId; ?>" novalidate>
      <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

      <div class="form-group">
        <label for="card_holder">Name on card</label>
        <input type="text" id="card_holder" name="card_holder" value="<?php echo old('card_holder'); ?>">
        <?php echo field_error('card_holder'); ?>
      </div>

      <div class="form-group">
        <label for="card_number">Card number</label>
        <input type="text" id="card_number" name="card_number" maxlength="19" placeholder="1234 5678 9012 3456" value="<?php echo old('card_number'); ?>">
        <?php echo field_error('card_number'); ?>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="expiry">Expiry (MM/YY)</label>
          <input type="text" id="expiry" name="expiry" maxlength="5" placeholder="08/28" value="<?php echo old('expiry'); ?>">
          <?php echo field_error('expiry'); ?>
        </div>
        <div class="form-group">
          <label for="cvv">CVV</label>
          <input type="text" id="cvv" name="cvv" maxlength="3" placeholder="123" value="<?php echo old('cvv'); ?>">
          <?php echo field_error('cvv'); ?>
        </div>
      </div>

      <button type="submit" class="btn btn-primary btn-block">Pay <?php echo money($booking['total_price']); ?></button>
    </form>
  </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/validation.js"></script>
<script>
validateForm('paymentForm', [
  { name: 'card_holder', required: true },
  { name: 'card_number', required: true },
  { name: 'expiry', required: true },
  { name: 'cvv', required: true }
]);
</script>

<?php
clear_old();
require_once __DIR__ . '/includes/footer.php';
?>
