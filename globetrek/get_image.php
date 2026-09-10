<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Package.php';

$id = (int) ($_GET['id'] ?? 0);
$image = $id ? Package::getImage($id) : null;

if (!$image) {
    // Fall back to a tiny transparent pixel so broken <img> tags don't show an ugly icon
    header('Content-Type: image/gif');
    echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBTAA7');
    exit;
}

header('Content-Type: ' . $image['type']);
header('Content-Length: ' . strlen($image['data']));
echo $image['data'];
