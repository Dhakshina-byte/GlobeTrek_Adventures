<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Package.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Query.php';

require_login('admin');

$totalStaff = User::countByRole('staff');
$totalCustomers = User::countByRole('customer');
$totalPackages = Package::countAll();
$totalBookings = Booking::countAll();
$revenue = Booking::totalRevenue();
$openQueries = InquiryQuery::countOpen();

$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="dash-header">
  <div class="container">
    <h1>Admin Dashboard</h1>
    <p>Oversee staff, bookings, and overall system performance.</p>
  </div>
</section>

<div class="container section">
  <div class="dash-stats">
    <div class="stat-card"><div class="num"><?php echo $totalStaff; ?></div><div class="label">Staff Accounts</div></div>
    <div class="stat-card"><div class="num"><?php echo $totalCustomers; ?></div><div class="label">Customers</div></div>
    <div class="stat-card"><div class="num"><?php echo $totalPackages; ?></div><div class="label">Packages</div></div>
    <div class="stat-card"><div class="num"><?php echo $totalBookings; ?></div><div class="label">Total Bookings</div></div>
    <div class="stat-card"><div class="num"><?php echo money($revenue); ?></div><div class="label">Revenue (paid)</div></div>
    <div class="stat-card"><div class="num"><?php echo $openQueries; ?></div><div class="label">Open Queries</div></div>
  </div>

  <div class="dash-tabs">
    <a href="<?php echo BASE_URL; ?>/admin/manage_staff.php">Manage Staff</a>
    <a href="<?php echo BASE_URL; ?>/admin/reports.php">Sales &amp; Customer Reports</a>
    <a href="<?php echo BASE_URL; ?>/staff/manage_packages.php">Manage Packages</a>
    <a href="<?php echo BASE_URL; ?>/staff/manage_bookings.php">Manage Bookings</a>
    <a href="<?php echo BASE_URL; ?>/staff/manage_queries.php">Customer Queries</a>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
