<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/models/Package.php';

$id = (int) ($_GET['id'] ?? 0);
$pkg = Package::getById($id);

if (!$pkg) {
    set_flash('error', 'That package could not be found.');
    redirect(BASE_URL . '/packages.php');
}

$pageTitle = $pkg['title'];
require_once __DIR__ . '/includes/header.php';
?>

<div class="container section">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:36px;align-items:start;">
    <div>
      <?php if (Package::hasImage($id)): ?>
        <img src="<?php echo BASE_URL; ?>/get_image.php?id=<?php echo $id; ?>" alt="<?php echo clean($pkg['title']); ?>" style="width:100%;border-radius:6px;max-height:380px;object-fit:cover;">
      <?php else: ?>
        <div class="no-image" style="height:300px;border-radius:6px;">No image yet</div>
      <?php endif; ?>
    </div>
    <div>
      <p class="package-meta"><?php echo clean($pkg['destination']); ?></p>
      <h1><?php echo clean($pkg['title']); ?></h1>
      <p class="text-muted"><?php echo (int) $pkg['duration_days']; ?> days / <?php echo (int) $pkg['duration_nights']; ?> nights</p>
      <p><?php echo nl2br(clean($pkg['description'])); ?></p>

      <?php if (!empty($pkg['activities'])): ?>
        <h3>Activities included</h3>
        <p><?php echo nl2br(clean($pkg['activities'])); ?></p>
      <?php endif; ?>

      <p class="price" style="font-size:1.4rem;margin-top:20px;"><?php echo money($pkg['price']); ?> <small>per person</small></p>

      <?php if (!is_logged_in()): ?>
        <a href="<?php echo BASE_URL; ?>/login.php" class="btn btn-primary">Log in to book</a>
      <?php elseif (current_role() === 'customer'): ?>
        <a href="<?php echo BASE_URL; ?>/book.php?id=<?php echo $id; ?>" class="btn btn-primary">Book this package</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
