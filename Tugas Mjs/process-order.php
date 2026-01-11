<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: auth/login.php");
  exit;
}

require __DIR__ . '/admin/db.php';

$customer_id = $_SESSION['customer_id'];

try {
  // =======================
  // VALIDASI INPUT
  // =======================
  $address = trim($_POST['shipping_address'] ?? '');
  $city = trim($_POST['city'] ?? '');
  $shipping_cost = (int)($_POST['shipping_cost'] ?? 0);
  $payment_method = $_POST['payment_method'] ?? '';
  $note = trim($_POST['note'] ?? '');
  $cart_json = $_POST['cart_json'] ?? '';

  if (!$address || !$city || !$payment_method || !$cart_json) {
    throw new Exception("Data checkout tidak lengkap.");
  }

  $cart = json_decode($cart_json, true);
  if (!is_array($cart) || count($cart) === 0) {
    throw new Exception("Keranjang kosong.");
  }

  // =======================
  // MULAI TRANSACTION
  // =======================
  $pdo->beginTransaction();

  // =======================
  // AMBIL HARGA + STOK TERKINI
  // =======================
  $productIds = array_column($cart, 'id');
  $placeholders = implode(',', array_fill(0, count($productIds), '?'));

  $stmt = $pdo->prepare("
    SELECT product_id, price, stock
    FROM products
    WHERE product_id IN ($placeholders)
    FOR UPDATE
  ");
  $stmt->execute($productIds);
  $rows = $stmt->fetchAll();

  if (!$rows) {
    throw new Exception("Produk tidak ditemukan.");
  }

  // map produk
  $products = [];
  foreach ($rows as $r) {
    $products[$r['product_id']] = [
      'price' => (int)$r['price'],
      'stock' => (int)$r['stock']
    ];
  }

  // =======================
  // HITUNG TOTAL + CEK STOK
  // =======================
  $subtotal = 0;

  foreach ($cart as $item) {
    $pid = (int)$item['id'];
    $qty = (int)$item['qty'];

    if (!isset($products[$pid])) {
      throw new Exception("Produk tidak valid.");
    }

    if ($products[$pid]['stock'] < $qty) {
      throw new Exception("Stok produk tidak mencukupi.");
    }

    $subtotal += $products[$pid]['price'] * $qty;
  }

  $total = $subtotal + $shipping_cost;

  // =======================
  // UPLOAD BUKTI TRANSFER
  // =======================
  $payment_proof = null;
  if ($payment_method === 'transfer' && !empty($_FILES['payment_proof']['name'])) {
    $dir = __DIR__ . '/assets/img/payments/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $ext = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
    $filename = 'pay_' . time() . '.' . $ext;

    if (!move_uploaded_file($_FILES['payment_proof']['tmp_name'], $dir . $filename)) {
      throw new Exception("Gagal upload bukti transfer.");
    }

    $payment_proof = 'assets/img/payments/' . $filename;
  }

  // =======================
  // SIMPAN ORDER
  // =======================
  $order_code = 'ORD-' . date('YmdHis');

  $stmt = $pdo->prepare("
    INSERT INTO orders (
      order_code,
      customer_id,
      shipping_address,
      city,
      shipping_cost,
      subtotal,
      total,
      payment_method,
      payment_proof,
      note
    ) VALUES (?,?,?,?,?,?,?,?,?,?)
  ");

  $stmt->execute([
    $order_code,
    $customer_id,
    $address,
    $city,
    $shipping_cost,
    $subtotal,
    $total,
    $payment_method,
    $payment_proof,
    $note
  ]);

  $order_id = $pdo->lastInsertId();

  // =======================
  // SIMPAN ORDER ITEMS + KURANGI STOK
  // =======================
  $stmtItem = $pdo->prepare("
    INSERT INTO order_items (order_id, product_id, price, qty, subtotal)
    VALUES (?,?,?,?,?)
  ");

  $stmtStock = $pdo->prepare("
    UPDATE products
    SET stock = stock - ?
    WHERE product_id = ? AND stock >= ?
  ");

  foreach ($cart as $item) {
    $pid = (int)$item['id'];
    $qty = (int)$item['qty'];
    $price = $products[$pid]['price'];

    // simpan item
    $stmtItem->execute([
      $order_id,
      $pid,
      $price,
      $qty,
      $price * $qty
    ]);

    // kurangi stok
    $stmtStock->execute([$qty, $pid, $qty]);

    if ($stmtStock->rowCount() === 0) {
      throw new Exception("Stok produk tidak mencukupi.");
    }
  }

  // =======================
  // COMMIT
  // =======================
  $pdo->commit();

  header("Location: order-success.php?code=$order_code");
  exit;

} catch (Exception $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }
$_SESSION['checkout_error'] = $e->getMessage();
header("Location: checkout.php");
exit;

}
