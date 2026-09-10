<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/models/Package.php';
require_once __DIR__ . '/controllers/BookingController.php';

require_login('customer');

$id = (int) ($_GET['id'] ?? 0);
$pkg = Package::getById($id);
if (!$pkg) {
    set_flash('error', 'That package could not be found.');
    redirect(BASE_URL . '/packages.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    BookingController::handleCreate($id);
}

$pageTitle = 'Book: ' . $pkg['title'];
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <div class="form-card wide">
    <h2>Customize your trip</h2>
    <p class="text-muted"><?php echo clean($pkg['title']); ?> — <?php echo clean($pkg['destination']); ?> (<?php echo money($pkg['price']); ?> per person)</p>

    <form id="bookingForm" method="POST" action="<?php echo BASE_URL; ?>/book.php?id=<?php echo $id; ?>" novalidate>
      <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

      <div class="form-row">
        <div class="form-group">
          <label for="travel_date">Travel date</label>
          <input type="date" id="travel_date" name="travel_date" min="<?php echo date('Y-m-d'); ?>" value="<?php echo old('travel_date'); ?>">
          <?php echo field_error('travel_date'); ?>
        </div>
        <div class="form-group">
          <label for="travelers">Number of travelers</label>
          <input type="number" id="travelers" name="travelers" min="1" max="20" value="<?php echo old('travelers', 1); ?>">
          <?php echo field_error('travelers'); ?>
        </div>
      </div>

      <div class="form-group">
        <label for="special_requests">Special requests (optional)</label>
        <textarea id="special_requests" name="special_requests" placeholder="E.g. dietary requirements, accessibility needs, room preferences"><?php echo old('special_requests'); ?></textarea>
      </div>

      <button type="submit" class="btn btn-primary btn-block">Continue to payment</button>
    </form>
  </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/validation.js"></script>
<script>
validateForm('bookingForm', [
  { name: 'travel_date', required: true },
  { name: 'travelers', required: true, min: 1 }
]);
</script>

<?php
clear_old();
require_once __DIR__ . '/includes/footer.php';
?>
