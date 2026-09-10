<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../controllers/AdminController.php';

require_login('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    AdminController::handleAddStaff();
}
if (isset($_GET['delete'])) {
    AdminController::handleDeleteStaff((int) $_GET['delete']);
}

$staff = User::getAllStaff();
$pageTitle = 'Manage Staff';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="dash-header">
  <div class="container">
    <h1>Manage Staff</h1>
    <p>Create and remove travel agency staff accounts.</p>
  </div>
</section>

<div class="container section">
  <div class="dash-tabs">
    <a href="<?php echo BASE_URL; ?>/admin/dashboard.php">Dashboard</a>
    <a href="<?php echo BASE_URL; ?>/admin/manage_staff.php" class="active">Manage Staff</a>
    <a href="<?php echo BASE_URL; ?>/admin/reports.php">Reports</a>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1.4fr;gap:30px;align-items:start;">
    <div class="form-card mt-0" style="margin:0;">
      <h3 class="mt-0">Add Staff Member</h3>
      <form id="staffForm" method="POST" action="<?php echo BASE_URL; ?>/admin/manage_staff.php" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

        <div class="form-group">
          <label for="name">Full name</label>
          <input type="text" id="name" name="name" value="<?php echo old('name'); ?>">
          <?php echo field_error('name'); ?>
        </div>
        <div class="form-group">
          <label for="email">Email address</label>
          <input type="email" id="email" name="email" value="<?php echo old('email'); ?>">
          <?php echo field_error('email'); ?>
        </div>
        <div class="form-group">
          <label for="phone">Phone number</label>
          <input type="tel" id="phone" name="phone" value="<?php echo old('phone'); ?>">
          <?php echo field_error('phone'); ?>
        </div>
        <div class="form-group">
          <label for="password">Temporary password</label>
          <input type="password" id="password" name="password">
          <?php echo field_error('password'); ?>
        </div>
        <div class="form-group">
          <label for="confirm_password">Confirm password</label>
          <input type="password" id="confirm_password" name="confirm_password">
          <?php echo field_error('confirm_password'); ?>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Add Staff</button>
      </form>
    </div>

    <div>
      <h3 class="mt-0">Current Staff</h3>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Joined</th><th></th></tr></thead>
          <tbody>
            <?php foreach ($staff as $s): ?>
              <tr>
                <td><?php echo clean($s['name']); ?></td>
                <td><?php echo clean($s['email']); ?></td>
                <td><?php echo clean($s['phone']); ?></td>
                <td><?php echo clean($s['created_at']); ?></td>
                <td>
                  <a href="<?php echo BASE_URL; ?>/admin/manage_staff.php?delete=<?php echo $s['user_id']; ?>"
                     onclick="return confirm('Remove this staff account?');">Remove</a>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($staff)): ?>
              <tr><td colspan="5" class="text-muted">No staff accounts yet.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/validation.js"></script>
<script>
validateForm('staffForm', [
  { name: 'name', required: true },
  { name: 'email', required: true, email: true },
  { name: 'phone', required: true },
  { name: 'password', required: true, minLength: 6 },
  { name: 'confirm_password', required: true, match: 'password' }
]);
</script>

<?php
clear_old();
require_once __DIR__ . '/../includes/footer.php';
?>
