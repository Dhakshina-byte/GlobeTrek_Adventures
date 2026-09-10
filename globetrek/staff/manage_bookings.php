<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../controllers/BookingController.php';

require_login(['staff', 'admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['status'])) {
    BookingController::handleStatusChange((int) $_POST['booking_id'], $_POST['status']);
}

$bookings = Booking::getAll();
$pageTitle = 'Manage Bookings';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="dash-header">
  <div class="container">
    <h1>Manage Bookings</h1>
    <p>Confirm bookings once travel and hotel arrangements are coordinated.</p>
  </div>
</section>

<div class="container section">
  <div class="dash-tabs">
    <a href="<?php echo BASE_URL; ?>/staff/dashboard.php">Dashboard</a>
    <a href="<?php echo BASE_URL; ?>/staff/manage_packages.php">Manage Packages</a>
    <a href="<?php echo BASE_URL; ?>/staff/manage_bookings.php" class="active">Manage Bookings</a>
    <a href="<?php echo BASE_URL; ?>/staff/manage_queries.php">Customer Queries</a>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Customer</th><th>Package</th><th>Travel date</th><th>Travelers</th><th>Total</th><th>Payment</th><th>Status</th><th>Update</th></tr>
      </thead>
      <tbody>
        <?php foreach ($bookings as $b): ?>
          <tr>
            <td><?php echo clean($b['customer_name']); ?><br><span class="text-muted"><?php echo clean($b['customer_email']); ?></span></td>
            <td><?php echo clean($b['title']); ?> <br><span class="text-muted"><?php echo clean($b['destination']); ?></span></td>
            <td><?php echo clean($b['travel_date']); ?></td>
            <td><?php echo (int) $b['travelers']; ?></td>
            <td><?php echo money($b['total_price']); ?></td>
            <td><span class="badge badge-<?php echo $b['payment_status']; ?>"><?php echo ucfirst($b['payment_status']); ?></span></td>
            <td><span class="badge badge-<?php echo $b['status']; ?>"><?php echo ucfirst($b['status']); ?></span></td>
            <td>
              <form method="POST" action="<?php echo BASE_URL; ?>/staff/manage_bookings.php" style="display:flex;gap:6px;">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                <input type="hidden" name="booking_id" value="<?php echo $b['booking_id']; ?>">
                <select name="status">
                  <option value="pending" <?php echo $b['status']==='pending'?'selected':''; ?>>Pending</option>
                  <option value="confirmed" <?php echo $b['status']==='confirmed'?'selected':''; ?>>Confirmed</option>
                  <option value="cancelled" <?php echo $b['status']==='cancelled'?'selected':''; ?>>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-outline-dark btn-sm">Save</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($bookings)): ?>
          <tr><td colspan="8" class="text-muted">No bookings yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
