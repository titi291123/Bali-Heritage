<?php
session_start();
require __DIR__ . '/../db.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
  $_SESSION['error'] = 'Username & password wajib diisi';
  header('Location: login.php');
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
$stmt->execute([$username]);
$admin = $stmt->fetch();

if (!$admin || !password_verify($password, $admin['password'])) {
  $_SESSION['error'] = 'Login gagal';
  header('Location: login.php');
  exit;
}

// LOGIN BERHASIL
$_SESSION['admin_id'] = $admin['admin_id'];
$_SESSION['admin_username'] = $admin['username'];

header('Location: ../index.php');
exit;
