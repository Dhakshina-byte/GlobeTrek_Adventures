<?php
require_once __DIR__ . '/functions.php';
$role = current_role();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($pageTitle) ? clean($pageTitle) . ' - GlobeTrek Adventures' : 'GlobeTrek Adventures'; ?></title>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>

<header class="site-header">
  <div class="container header-inner">
    <a href="<?php echo BASE_URL; ?>/index.php" class="logo"><span class="accent">Globe</span>Trek Adventures</a>
    <nav class="main-nav">
      <a href="<?php echo BASE_URL; ?>/index.php">Home</a>
      <a href="<?php echo BASE_URL; ?>/packages.php">Packages</a>
      <a href="<?php echo BASE_URL; ?>/contact.php">Contact</a>

      <?php if (!is_logged_in()): ?>
        <a href="<?php echo BASE_URL; ?>/login.php">Log in</a>
        <a href="<?php echo BASE_URL; ?>/register.php" class="btn btn-primary btn-sm">Register</a>

      <?php elseif ($role === 'customer'): ?>
        <a href="<?php echo BASE_URL; ?>/my_bookings.php">My Bookings</a>
        <a href="<?php echo BASE_URL; ?>/logout.php">Log out (<?php echo clean($_SESSION['name']); ?>)</a>

      <?php elseif ($role === 'staff'): ?>
        <a href="<?php echo BASE_URL; ?>/staff/dashboard.php">Staff Dashboard</a>
        <a href="<?php echo BASE_URL; ?>/logout.php">Log out (<?php echo clean($_SESSION['name']); ?>)</a>

      <?php elseif ($role === 'admin'): ?>
        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php">Admin Dashboard</a>
        <a href="<?php echo BASE_URL; ?>/logout.php">Log out (<?php echo clean($_SESSION['name']); ?>)</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<main>
<?php foreach (get_flashes() as $flash): ?>
  <div class="container">
    <div class="alert alert-<?php echo $flash['type'] === 'error' ? 'error' : 'success'; ?>">
      <?php echo clean($flash['message']); ?>
    </div>
  </div>
<?php endforeach; ?>
