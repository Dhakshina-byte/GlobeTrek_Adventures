<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Query.php';
require_once __DIR__ . '/../controllers/QueryController.php';

require_login(['staff', 'admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query_id'])) {
    QueryController::handleReply((int) $_POST['query_id']);
}

$queries = InquiryQuery::getAll();
$pageTitle = 'Customer Queries';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="dash-header">
  <div class="container">
    <h1>Customer Queries</h1>
    <p>Respond to travel arrangement questions submitted through the Contact page.</p>
  </div>
</section>

<div class="container section">
  <div class="dash-tabs">
    <a href="<?php echo BASE_URL; ?>/staff/dashboard.php">Dashboard</a>
    <a href="<?php echo BASE_URL; ?>/staff/manage_packages.php">Manage Packages</a>
    <a href="<?php echo BASE_URL; ?>/staff/manage_bookings.php">Manage Bookings</a>
    <a href="<?php echo BASE_URL; ?>/staff/manage_queries.php" class="active">Customer Queries</a>
  </div>

  <?php if (empty($queries)): ?>
    <p class="text-muted">No queries submitted yet.</p>
  <?php else: ?>
    <?php foreach ($queries as $q): ?>
      <div class="form-card wide" style="margin:0 0 20px;">
        <div style="display:flex;justify-content:space-between;">
          <div>
            <h3 class="mt-0 mb-0"><?php echo clean($q['subject']); ?></h3>
            <p class="text-muted" style="margin:2px 0;"><?php echo clean($q['name']); ?> — <?php echo clean($q['email']); ?> · <?php echo clean($q['created_at']); ?></p>
          </div>
          <span class="badge badge-<?php echo $q['status']; ?>"><?php echo ucfirst($q['status']); ?></span>
        </div>
        <p><?php echo nl2br(clean($q['message'])); ?></p>

        <?php if ($q['status'] === 'answered'): ?>
          <div class="alert alert-success"><strong>Reply sent:</strong> <?php echo nl2br(clean($q['reply'])); ?></div>
        <?php else: ?>
          <form method="POST" action="<?php echo BASE_URL; ?>/staff/manage_queries.php">
            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="query_id" value="<?php echo $q['query_id']; ?>">
            <div class="form-group">
              <label>Reply to <?php echo clean($q['name']); ?></label>
              <textarea name="reply" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Send Reply</button>
          </form>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
