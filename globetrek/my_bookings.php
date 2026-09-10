<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/models/Booking.php';

require_login('customer');
$bookings = Booking::getByUser($_SESSION['user_id']);

$pageTitle = 'My Bookings';
require_once __DIR__ . '/includes/header.php';
?>

<section class="dash-header">
  <div class="container">
    <h1>My Bookings</h1>
    <p>Everything you've booked with GlobeTrek Adventures.</p>
  </div>
</section>

<div class="container section">
  <?php if (empty($bookings)): ?>
    <p class="text-muted">You haven't booked a package yet. <a href="<?php echo BASE_URL; ?>/packages.php">Browse packages</a>.</p>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Package</th><th>Travel date</th><th>Travelers</th><th>Total</th><th>Status</th><th>Payment</th><th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($bookings as $b): ?>
            <tr>
              <td><?php echo clean($b['title']); ?><br><span class="text-muted"><?php echo clean($b['destination']); ?></span></td>
              <td><?php echo clean($b['travel_date']); ?></td>
              <td><?php echo (int) $b['travelers']; ?></td>
              <td><?php echo money($b['total_price']); ?></td>
              <td><span class="badge badge-<?php echo $b['status']; ?>"><?php echo ucfirst($b['status']); ?></span></td>
              <td><span class="badge badge-<?php echo $b['payment_status']; ?>"><?php echo ucfirst($b['payment_status']); ?></span></td>
              <td>
                <?php if ($b['payment_status'] === 'unpaid'): ?>
                  <a href="<?php echo BASE_URL; ?>/payment.php?booking_id=<?php echo $b['booking_id']; ?>" class="btn btn-primary btn-sm">Pay now</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
