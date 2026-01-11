<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

require __DIR__ . '/../admin/db.php';

$customer_id = $_SESSION['customer_id'];

// Ambil semua order customer
$stmt = $pdo->prepare("
  SELECT 
    order_id,
    order_code,
    created_at,
    total,
    payment_method,
    status
  FROM orders
  WHERE customer_id = ?
  ORDER BY created_at DESC
");
$stmt->execute([$customer_id]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Riwayat Pesanan — Bali Heritage Wear</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="icon" href="../assets/img/logo.png" type="image/png">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm" id="mainNav">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="index.html">
        <img src="../assets/img/logo.png" alt="logo" width="44" height="44" class="rounded">
        <span class="brand-title">Bali Heritage Wear</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item"><a class="nav-link" href="../index.html">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="../informasi.html">Informasi</a></li>
          <li class="nav-item"><a class="nav-link" href="../product.html">Produk</a></li>
          <li class="nav-item">
            <a class="nav-link" href="../cart.html">
              <i class="bi bi-cart3 me-1"></i>Keranjang
              <span id="cart-count" class="badge bg-danger ms-1">0</span>
            </a>
          </li>
          <li class="nav-item"><a class="nav-link active" href="index.php">Akun Saya</a></li>
        </ul>
      </div>
    </div>
  </nav>

<section class="py-5 bg-soft">
  <div class="container">
    <h3 class="fw-bold mb-4">Riwayat Pesanan</h3>

    <?php if (count($orders) === 0): ?>
      <div class="alert alert-info">
        Kamu belum pernah melakukan pesanan.
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered bg-white align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Tanggal</th>
              <th>Total</th>
              <th>Pembayaran</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $i => $order): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                <td class="fw-semibold">
                  Rp<?= number_format($order['total'], 0, ',', '.') ?>
                </td>
                <td>
                  <?= strtoupper($order['payment_method']) ?>
                </td>
                <td>
                  <?php
                    $badge = match($order['status']) {
                      'pending' => 'warning',
                      'paid' => 'primary',
                      'processing' => 'info',
                      'shipped' => 'secondary',
                      'completed' => 'success',
                      'cancelled' => 'danger',
                      default => 'dark'
                    };
                  ?>
                  <span class="badge bg-<?= $badge ?>">
                    <?= ucfirst($order['status']) ?>
                  </span>
                </td>
                <td>
                  <a href="order-detail.php?id=<?= $order['order_id'] ?>"
                     class="btn btn-sm btn-outline-primary">
                    Detail
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <a href="index.php" class="btn btn-secondary mt-3">
      ← Kembali ke Dashboard
    </a>

  </div>
</section>

<!-- FOOTER -->
  <footer class="bg-dark text-light pt-5">
    <div class="container pb-3">
      <div class="row gy-4">
        <div class="col-md-4">
          <h5 class="fw-bold mb-3 text-white">Tentang Kami</h5>
          <p class="small text-light opacity-75">
            Bali Heritage Wear adalah UMKM yang memproduksi pakaian adat Bali seperti udeng, kamen, dan baju adat.
            Kami berkomitmen menjaga warisan budaya dengan gaya modern dan berkelas.
          </p>
        </div>

        <div class="col-md-4">
          <h5 class="fw-bold mb-3 text-white">Sosial Media Kami</h5>
          <div class="d-flex gap-3 fs-4">
            <a href="#" class="text-light"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-light"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-light"><i class="bi bi-whatsapp"></i></a>
            <a href="#" class="text-light"><i class="bi bi-envelope"></i></a>
          </div>
        </div>

        <div class="col-md-4">
          <h5 class="fw-bold mb-3 text-white">Kontak Kami</h5>
          <ul class="list-unstyled small">
            <li class="mb-2">
              <i class="bi bi-whatsapp me-2 text-warning"></i>
              <span class="text-light">+62 812-3456-7890</span>
            </li>
            <li class="mb-2">
              <i class="bi bi-envelope me-2 text-warning"></i>
              <span class="text-light">info@baliheritagewear.com</span>
            </li>
            <li class="mb-2">
              <i class="bi bi-geo-alt me-2 text-warning"></i>
              <span class="text-light">Denpasar, Bali</span>
            </li>
            <li>
              <i class="bi bi-clock me-2 text-warning"></i>
              <span class="text-light">Senin - Minggu | 07:00 – 22:00</span>
            </li>
          </ul>
        </div>
      </div>

      <hr class="border-light opacity-25 mt-4">
      <p class="text-center small text-secondary mb-0">
        © 2025 <span class="text-light">Bali Heritage Wear</span> — Semua Hak Dilindungi
      </p>
    </div>
  </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/cart.js"></script>
<script>
updateCartBadge();
</script>


</body>
</html>
