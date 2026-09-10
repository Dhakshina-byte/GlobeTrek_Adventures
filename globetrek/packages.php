<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/controllers/PackageController.php';

$packages = PackageController::listPackages();
$search = clean($_GET['search'] ?? '');

$pageTitle = 'Packages';
require_once __DIR__ . '/includes/header.php';
?>

<section class="dash-header">
  <div class="container">
    <h1>Tour Packages</h1>
    <p>Browse everything GlobeTrek Adventures currently offers.</p>
  </div>
</section>

<div class="container section">
  <form method="GET" action="<?php echo BASE_URL; ?>/packages.php" style="max-width:420px;margin-bottom:10px;display:flex;gap:10px;">
    <input type="text" name="search" placeholder="Search by title or destination" value="<?php echo $search; ?>">
    <button type="submit" class="btn btn-outline-dark">Search</button>
  </form>

  <?php if (empty($packages)): ?>
    <p class="text-muted">No packages found<?php echo $search ? ' for "' . $search . '"' : ''; ?>.</p>
  <?php else: ?>
    <div class="package-grid">
      <?php foreach ($packages as $pkg): ?>
        <div class="package-card">
          <?php if (Package::hasImage($pkg['package_id'])): ?>
            <img src="<?php echo BASE_URL; ?>/get_image.php?id=<?php echo $pkg['package_id']; ?>" alt="<?php echo clean($pkg['title']); ?>">
          <?php else: ?>
            <div class="no-image">No image yet</div>
          <?php endif; ?>
          <div class="package-body">
            <p class="package-meta"><?php echo clean($pkg['destination']); ?> · <?php echo (int) $pkg['duration_days']; ?> days / <?php echo (int) $pkg['duration_nights']; ?> nights</p>
            <h3><?php echo clean($pkg['title']); ?></h3>
            <p class="package-desc"><?php echo clean(mb_strimwidth($pkg['description'], 0, 100, '...')); ?></p>
            <div class="package-footer">
              <span class="price"><?php echo money($pkg['price']); ?> <small>per person</small></span>
              <a href="<?php echo BASE_URL; ?>/package_details.php?id=<?php echo $pkg['package_id']; ?>" class="btn btn-outline-dark btn-sm">View details</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
