<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Package.php';
require_once __DIR__ . '/../controllers/PackageController.php';

require_login(['staff', 'admin']);

if (isset($_GET['delete'])) {
    PackageController::handleDelete((int) $_GET['delete']);
}

$packages = Package::getAll();
$pageTitle = 'Manage Packages';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="dash-header">
  <div class="container">
    <h1>Manage Packages</h1>
    <p>Add, edit, or remove tour packages — including their picture.</p>
  </div>
</section>

<div class="container section">
  <div class="dash-tabs">
    <a href="<?php echo BASE_URL; ?>/staff/dashboard.php">Dashboard</a>
    <a href="<?php echo BASE_URL; ?>/staff/manage_packages.php" class="active">Manage Packages</a>
    <a href="<?php echo BASE_URL; ?>/staff/manage_bookings.php">Manage Bookings</a>
    <a href="<?php echo BASE_URL; ?>/staff/manage_queries.php">Customer Queries</a>
  </div>

  <a href="<?php echo BASE_URL; ?>/staff/package_form.php" class="btn btn-primary" style="margin-bottom:16px;display:inline-block;">+ Add New Package</a>

  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Picture</th><th>Title</th><th>Destination</th><th>Price</th><th>Duration</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php foreach ($packages as $pkg): ?>
          <tr>
            <td>
              <?php if (Package::hasImage($pkg['package_id'])): ?>
                <img class="current-image" src="<?php echo BASE_URL; ?>/get_image.php?id=<?php echo $pkg['package_id']; ?>" alt="">
              <?php else: ?>
                <span class="text-muted">No image</span>
              <?php endif; ?>
            </td>
            <td><?php echo clean($pkg['title']); ?></td>
            <td><?php echo clean($pkg['destination']); ?></td>
            <td><?php echo money($pkg['price']); ?></td>
            <td><?php echo (int) $pkg['duration_days']; ?>D / <?php echo (int) $pkg['duration_nights']; ?>N</td>
            <td class="actions-cell">
              <a href="<?php echo BASE_URL; ?>/staff/package_form.php?id=<?php echo $pkg['package_id']; ?>">Edit</a>
              <a href="<?php echo BASE_URL; ?>/staff/manage_packages.php?delete=<?php echo $pkg['package_id']; ?>"
                 onclick="return confirm('Delete this package? This cannot be undone.');">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($packages)): ?>
          <tr><td colspan="6" class="text-muted">No packages yet. Add your first one above.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
