<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

require __DIR__ . '/../admin/db.php';

$customer_id = $_SESSION['customer_id'];

$name = trim($_POST['name'] ?? '');
$whatsapp = trim($_POST['whatsapp'] ?? '');
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (!$name || !$whatsapp) {
  die("Nama dan WhatsApp wajib diisi.");
}

/* =========================
   UPDATE DATA DASAR
========================= */
$stmt = $pdo->prepare("
  UPDATE customers 
  SET name = ?, whatsapp = ?
  WHERE customer_id = ?
");
$stmt->execute([$name, $whatsapp, $customer_id]);

/* =========================
   UPDATE PASSWORD (JIKA ADA)
========================= */
if ($new_password) {
  if (strlen($new_password) < 6) {
    die("Password minimal 6 karakter.");
  }

  if ($new_password !== $confirm_password) {
    die("Konfirmasi password tidak cocok.");
  }

  $hash = password_hash($new_password, PASSWORD_DEFAULT);

  $stmt = $pdo->prepare("
    UPDATE customers
    SET password = ?
    WHERE customer_id = ?
  ");
  $stmt->execute([$hash, $customer_id]);
}

header("Location: profile.php?success=1");
exit;
