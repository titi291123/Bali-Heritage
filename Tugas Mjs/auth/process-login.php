<?php
session_start();
require __DIR__ . '/../admin/db.php';

$email = trim($_POST['email']);
$pass  = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM customers WHERE email=? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($pass, $user['password'])) {
  die("Login gagal");
}

$_SESSION['customer_id'] = $user['customer_id'];
$_SESSION['customer_name'] = $user['name'];

header("Location: ../index.html");
exit;
