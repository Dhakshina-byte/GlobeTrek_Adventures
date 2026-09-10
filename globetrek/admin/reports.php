<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/User.php';

require_login('admin');

$bookings = Booking::getAll();
$totalRevenue = Booking::totalRevenue();
$totalCustomers = User::countByRole('customer');

$pageTitle = 'Reports';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="dash-header">
  <div class="container">
    <h1>Sales &amp; Customer Report</h1>
    <p>An overview of bookings and revenue across the platform.</p>
  </div>
</section>

<div class="container section">
  <div class="dash-tabs">
    <a href="<?php echo BASE_URL; ?>/admin/dashboard.php">Dashboard</a>
    <a href="<?php echo BASE_URL; ?>/admin/manage_staff.php">Manage Staff</a>
    <a href="<?php echo BASE_URL; ?>/admin/reports.php" class="active">Reports</a>
  </div>

  <div class="dash-stats">
    <div class="stat-card"><div class="num"><?php echo count($bookings); ?></div><div class="label">Total Bookings</div></div>
    <div class="stat-card"><div class="num"><?php echo money($totalRevenue); ?></div><div class="label">Confirmed Revenue</div></div>
    <div class="stat-card"><div class="num"><?php echo $totalCustomers; ?></div><div class="label">Registered Customers</div></div>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Date</th><th>Customer</th><th>Package</th><th>Travelers</th><th>Total</th><th>Payment</th><th>Status</th></tr>
      </thead>
      <tbody>
        <?php foreach ($bookings as $b): ?>
          <tr>
            <td><?php echo clean($b['created_at']); ?></td>
            <td><?php echo clean($b['customer_name']); ?></td>
            <td><?php echo clean($b['title']); ?></td>
            <td><?php echo (int) $b['travelers']; ?></td>
            <td><?php echo money($b['total_price']); ?></td>
            <td><span class="badge badge-<?php echo $b['payment_status']; ?>"><?php echo ucfirst($b['payment_status']); ?></span></td>
            <td><span class="badge badge-<?php echo $b['status']; ?>"><?php echo ucfirst($b['status']); ?></span></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($bookings)): ?>
          <tr><td colspan="7" class="text-muted">No bookings yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
