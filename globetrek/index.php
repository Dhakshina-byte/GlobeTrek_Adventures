<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/models/Package.php';

$pageTitle = 'Home';
$featured = array_slice(Package::getAll(), 0, 3);

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div class="container">
    <p class="eyebrow">GlobeTrek Adventures — Negombo, Sri Lanka</p>
    <h1>Travel, planned <em>with care</em>, from the first enquiry to the final night.</h1>
    <p>We design and book complete journeys across Sri Lanka — tour packages, stays and transport handled by one team, so you can focus on the trip itself.</p>
    <div class="actions">
      <a href="<?php echo BASE_URL; ?>/packages.php" class="btn btn-primary">Explore packages</a>
      <?php if (!is_logged_in()): ?>
        <a href="<?php echo BASE_URL; ?>/register.php" class="btn btn-outline">Create an account</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <p class="eyebrow">Why travel with us</p>
    <h2>Three things we take off your plate</h2>
    <p class="lead">From the first booking to the last transfer, one team manages every moving part of your itinerary.</p>

    <div class="grid-3">
      <div class="feature-card">
        <p class="tag">Curated</p>
        <h3>Packages built by locals</h3>
        <p>Every itinerary is shaped by our own travel consultants, drawing on relationships with hotels and guides across the island.</p>
      </div>
      <div class="feature-card">
        <p class="tag">Trusted</p>
        <h3>Vetted stays &amp; transport</h3>
        <p>We work only with accommodation and transport partners we've personally inspected, so standards stay consistent.</p>
      </div>
      <div class="feature-card">
        <p class="tag">Responsive</p>
        <h3>A team you can reach</h3>
        <p>Queries about bookings or itinerary changes go directly to our staff — no call centres, no long queues.</p>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-header-flex">
      <div>
        <p class="eyebrow">Featured</p>
        <h2>A few journeys to start with</h2>
      </div>
      <a href="<?php echo BASE_URL; ?>/packages.php" class="btn btn-outline-dark btn-sm">See all packages</a>
    </div>

    <?php if (empty($featured)): ?>
      <p class="text-muted">Packages will appear here once staff add them.</p>
    <?php else: ?>
      <div class="package-grid">
        <?php foreach ($featured as $pkg): ?>
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
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
