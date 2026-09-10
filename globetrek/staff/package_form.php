<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Package.php';
require_once __DIR__ . '/../controllers/PackageController.php';

require_login(['staff', 'admin']);

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$pkg = $id ? Package::getById($id) : null;

if ($id && !$pkg) {
    set_flash('error', 'That package could not be found.');
    redirect(BASE_URL . '/staff/manage_packages.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($id) {
        PackageController::handleUpdate($id);
    } else {
        PackageController::handleCreate();
    }
}

$pageTitle = $id ? 'Edit Package' : 'Add Package';
require_once __DIR__ . '/../includes/header.php';

// Values shown in the form: previously submitted (on validation error) > existing package > blank
function form_value($field, $pkg) {
    if (isset($_SESSION['old'][$field])) {
        return old($field);
    }
    return $pkg ? clean($pkg[$field]) : '';
}
?>

<div class="container">
  <div class="form-card wide">
    <h2><?php echo $id ? 'Edit Package' : 'Add New Package'; ?></h2>

    <form id="packageForm" method="POST"
          action="<?php echo BASE_URL; ?>/staff/package_form.php<?php echo $id ? '?id=' . $id : ''; ?>"
          enctype="multipart/form-data" novalidate>
      <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

      <div class="form-group">
        <label for="title">Package title</label>
        <input type="text" id="title" name="title" value="<?php echo form_value('title', $pkg); ?>">
        <?php echo field_error('title'); ?>
      </div>

      <div class="form-group">
        <label for="destination">Destination</label>
        <input type="text" id="destination" name="destination" value="<?php echo form_value('destination', $pkg); ?>">
        <?php echo field_error('destination'); ?>
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description"><?php echo form_value('description', $pkg); ?></textarea>
        <?php echo field_error('description'); ?>
      </div>

      <div class="form-group">
        <label for="activities">Activities included (optional, one per line)</label>
        <textarea id="activities" name="activities"><?php echo form_value('activities', $pkg); ?></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="price">Price per person (USD)</label>
          <input type="number" step="0.01" min="0" id="price" name="price" value="<?php echo form_value('price', $pkg); ?>">
          <?php echo field_error('price'); ?>
        </div>
        <div class="form-group">
          <label for="duration_days">Duration (days)</label>
          <input type="number" min="1" id="duration_days" name="duration_days" value="<?php echo form_value('duration_days', $pkg); ?>">
          <?php echo field_error('duration_days'); ?>
        </div>
        <div class="form-group">
          <label for="duration_nights">Duration (nights)</label>
          <input type="number" min="0" id="duration_nights" name="duration_nights" value="<?php echo form_value('duration_nights', $pkg); ?>">
          <?php echo field_error('duration_nights'); ?>
        </div>
      </div>

      <div class="form-group">
        <label for="image">Package picture (JPG, PNG or WEBP, max 2MB)</label>
        <?php if ($pkg && Package::hasImage($id)): ?>
          <div style="margin-bottom:10px;">
            <img class="current-image" src="<?php echo BASE_URL; ?>/get_image.php?id=<?php echo $id; ?>" alt="Current picture">
            <p class="text-muted" style="font-size:.8rem;">Current picture — upload a new one to replace it.</p>
          </div>
        <?php endif; ?>
        <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/webp">
        <?php echo field_error('image'); ?>
      </div>

      <button type="submit" class="btn btn-primary btn-block"><?php echo $id ? 'Save Changes' : 'Add Package'; ?></button>
    </form>
  </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/validation.js"></script>
<script>
validateForm('packageForm', [
  { name: 'title', required: true },
  { name: 'destination', required: true },
  { name: 'description', required: true },
  { name: 'price', required: true, min: 0.01 },
  { name: 'duration_days', required: true, min: 1 }
]);
</script>

<?php
clear_old();
require_once __DIR__ . '/../includes/footer.php';
?>
