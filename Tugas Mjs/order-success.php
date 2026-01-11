<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: auth/login.php");
  exit;
}

$orderCode = $_GET['code'] ?? null;
if (!$orderCode) {
  header("Location: index.html");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Pesanan Berhasil — Bali Heritage Wear</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-7">

      <div class="card shadow-sm border-0">
        <div class="card-body text-center p-5">

          <i class="bi bi-check-circle-fill text-success" style="font-size:64px"></i>

          <h3 class="fw-bold mt-3">Pesanan Berhasil Dibuat 🎉</h3>

          <p class="text-muted mt-2">
            Terima kasih telah berbelanja di <strong>Bali Heritage Wear</strong>.
          </p>

          <hr>

          <p class="mb-1">Kode Pesanan:</p>
          <h5 class="fw-bold text-brown"><?= htmlspecialchars($orderCode) ?></h5>

          <p class="small text-muted mt-3">
            Silakan lakukan pembayaran sesuai metode yang Anda pilih.
            <br>Admin kami akan memproses pesanan Anda setelah diverifikasi.
          </p>

          <div class="d-grid gap-2 mt-4">
            <a href="index.html" class="btn btn-primary">
              Kembali ke Beranda
            </a>
            <a href="product.html" class="btn btn-outline-secondary">
              Belanja Lagi
            </a>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

<footer class="text-center small text-muted py-3">
  © 2025 Bali Heritage Wear
</footer>

<script>
  // 🔥 PENTING: kosongkan cart setelah order sukses
  localStorage.removeItem('bhw_cart_v1');
</script>

</body>
</html>
