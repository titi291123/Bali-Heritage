<?php
session_start();
if (!isset($_SESSION['customer'])) {
  header("Location: ../auth/login.php");
  exit;
}
?>
<!doctype html>
<html>
<head>
  <title>Dashboard Customer</title>
</head>
<body>
  <h3>Halo, <?= htmlspecialchars($_SESSION['customer']['name']) ?></h3>
  <p>Anda sudah login.</p>

  <a href="../auth/logout.php">Logout</a>
</body>
</html>
