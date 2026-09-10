<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/controllers/QueryController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    QueryController::handleSubmit();
}

$pageTitle = 'Contact Us';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <div class="form-card">
    <h2>Get in touch</h2>
    <p class="text-muted">Have a question about a package or an existing booking? Send us a message and our travel team will get back to you.</p>

    <form id="contactForm" method="POST" action="<?php echo BASE_URL; ?>/contact.php" novalidate>
      <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

      <div class="form-group">
        <label for="name">Your name</label>
        <input type="text" id="name" name="name" value="<?php echo is_logged_in() ? clean($_SESSION['name']) : old('name'); ?>">
        <?php echo field_error('name'); ?>
      </div>

      <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" value="<?php echo old('email'); ?>">
        <?php echo field_error('email'); ?>
      </div>

      <div class="form-group">
        <label for="subject">Subject</label>
        <input type="text" id="subject" name="subject" value="<?php echo old('subject'); ?>">
        <?php echo field_error('subject'); ?>
      </div>

      <div class="form-group">
        <label for="message">Message</label>
        <textarea id="message" name="message"><?php echo old('message'); ?></textarea>
        <?php echo field_error('message'); ?>
      </div>

      <button type="submit" class="btn btn-primary btn-block">Send message</button>
    </form>
  </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/validation.js"></script>
<script>
validateForm('contactForm', [
  { name: 'name', required: true },
  { name: 'email', required: true, email: true },
  { name: 'subject', required: true },
  { name: 'message', required: true }
]);
</script>

<?php
clear_old();
require_once __DIR__ . '/includes/footer.php';
?>
