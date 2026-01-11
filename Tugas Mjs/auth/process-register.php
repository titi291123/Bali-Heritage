<?php
require __DIR__ . '/../admin/db.php';

$name     = trim($_POST['name']);
$email    = trim($_POST['email']);
$whatsapp = trim($_POST['whatsapp']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// cek email
$stmt = $pdo->prepare("SELECT customer_id FROM customers WHERE email=?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
  die("Email sudah terdaftar");
}

$stmt = $pdo->prepare("
  INSERT INTO customers (name, email, whatsapp, password)
  VALUES (?, ?, ?, ?)
");
$stmt->execute([$name, $email, $whatsapp, $password]);

header("Location: login.php");
exit;
