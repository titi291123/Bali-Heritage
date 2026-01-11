<?php
require __DIR__ . '/../db.php';

// VALIDASI GAMBAR WAJIB
if (
    !isset($_FILES['image']) ||
    $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE
) {
    header("Location: create.php?error=image_required");
    exit;
}

$name = trim($_POST['name'] ?? '');
$category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
$price = (int)($_POST['price'] ?? 0);
$discount_price = !empty($_POST['discount_price']) ? (int)$_POST['discount_price'] : null;
$stock = (int)($_POST['stock'] ?? 0);
$short_description = trim($_POST['short_description'] ?? '');
$slug = strtolower(preg_replace('/[^a-z0-9]+/i','-', trim($name)));

$uploadDir = __DIR__ . '/../../assets/img/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$filename = time() . '_' . preg_replace('/[^a-z0-9\.\-_]/i','', $_FILES['image']['name']);
$target = $uploadDir . $filename;

// MIME check
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$type = finfo_file($finfo, $_FILES['image']['tmp_name']);
finfo_close($finfo);

if (!in_array($type, ['image/jpeg','image/png','image/webp'])) {
    header("Location: create.php?error=invalid_image");
    exit;
}

if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
    header("Location: create.php?error=upload_failed");
    exit;
}

$imagePath = 'assets/img/' . $filename;

$stmt = $pdo->prepare("
  INSERT INTO products
  (category_id, name, slug, short_description, price, discount_price, stock, image_main)
  VALUES (?,?,?,?,?,?,?,?)
");

$stmt->execute([
  $category_id,
  $name,
  $slug,
  $short_description,
  $price,
  $discount_price,
  $stock,
  $imagePath
]);

header("Location: index_product.php");
exit;
