<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/controllers/AuthController.php';

if (is_logged_in()) {
    redirect(BASE_URL . '/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    AuthController::handleLogin();
}

$pageTitle = 'Log in';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <div class="form-card">
    <h2>Log in</h2>
    <p class="text-muted">Welcome back to GlobeTrek Adventures.</p>

    <?php if (!empty($_SESSION['errors']['general'])): ?>
      <div class="alert alert-error"><?php echo clean($_SESSION['errors']['general']); ?></div>
    <?php endif; ?>

    <form id="loginForm" method="POST" action="<?php echo BASE_URL; ?>/login.php" novalidate>
      <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

      <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" value="<?php echo old('email'); ?>">
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
      </div>

      <button type="submit" class="btn btn-primary btn-block">Log in</button>
    </form>

    <p class="text-muted" style="margin-top:16px">New here? <a href="<?php echo BASE_URL; ?>/register.php">Create an account</a></p>
    <p class="text-muted" style="font-size:.82rem">Demo admin login: admin@globetrek.com / Admin@123</p>
  </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/validation.js"></script>
<script>
validateForm('loginForm', [
  { name: 'email', required: true, email: true },
  { name: 'password', required: true }
]);
</script>

<?php
clear_old();
require_once __DIR__ . '/includes/footer.php';
?>
