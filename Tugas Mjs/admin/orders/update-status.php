<?php
require __DIR__ . '/../db.php';

$order_id = (int)($_POST['order_id'] ?? 0);
$status = $_POST['status'] ?? '';

if (!$order_id || !$status) {
  die("Data tidak valid");
}

// Ambil status lama
$stmt = $pdo->prepare("SELECT status FROM orders WHERE order_id = ?");
$stmt->execute([$order_id]);
$oldStatus = $stmt->fetchColumn();
if (!$oldStatus) die("Pesanan tidak ditemukan");

// =======================
// RESTOK JIKA DIBATALKAN
// =======================
if ($status === 'cancelled' && $oldStatus !== 'cancelled') {

  $items = $pdo->prepare("
    SELECT product_id, qty
    FROM order_items
    WHERE order_id = ?
  ");
  $items->execute([$order_id]);

  $stmtStock = $pdo->prepare("
    UPDATE products
    SET stock = stock + ?
    WHERE product_id = ?
  ");

  foreach ($items as $i) {
    $stmtStock->execute([
      $i['qty'],
      $i['product_id']
    ]);
  }
}

// =======================
// UPDATE STATUS ORDER
// =======================
$stmt = $pdo->prepare("
  UPDATE orders
  SET status = ?
  WHERE order_id = ?
");
$stmt->execute([$status, $order_id]);

header("Location: detail.php?id=$order_id");
exit;
