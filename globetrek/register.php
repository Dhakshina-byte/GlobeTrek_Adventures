<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/controllers/AuthController.php';

if (is_logged_in()) {
    redirect(BASE_URL . '/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    AuthController::handleRegister();
}

$pageTitle = 'Register';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <div class="form-card">
    <h2>Create your account</h2>
    <p class="text-muted">Join GlobeTrek Adventures to browse and book tour packages.</p>

    <?php if (!empty($_SESSION['errors']['general'])): ?>
      <div class="alert alert-error"><?php echo clean($_SESSION['errors']['general']); ?></div>
    <?php endif; ?>

    <form id="registerForm" method="POST" action="<?php echo BASE_URL; ?>/register.php" novalidate>
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
        <input type="tel" id="phone" name="phone" value="<?php echo old('phone'); ?>" placeholder="07XXXXXXXX">
        <?php echo field_error('phone'); ?>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
        <?php echo field_error('password'); ?>
      </div>

      <div class="form-group">
        <label for="confirm_password">Confirm password</label>
        <input type="password" id="confirm_password" name="confirm_password">
        <?php echo field_error('confirm_password'); ?>
      </div>

      <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>

    <p class="text-muted" style="margin-top:16px">Already have an account? <a href="<?php echo BASE_URL; ?>/login.php">Log in</a></p>
  </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/validation.js"></script>
<script>
validateForm('registerForm', [
  { name: 'name', required: true },
  { name: 'email', required: true, email: true },
  { name: 'phone', required: true },
  { name: 'password', required: true, minLength: 6 },
  { name: 'confirm_password', required: true, match: 'password' }
]);
</script>

<?php
clear_old();
require_once __DIR__ . '/includes/footer.php';
?>
