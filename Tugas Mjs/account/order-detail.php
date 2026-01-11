<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

require __DIR__ . '/../admin/db.php';

$customer_id = $_SESSION['customer_id'];
$order_id = (int) ($_GET['id'] ?? 0);

if (!$order_id) {
  die("Order tidak valid.");
}

/* =========================
   AMBIL DATA ORDER (AMAN)
========================= */
$stmt = $pdo->prepare("
  SELECT *
  FROM orders
  WHERE order_id = ? AND customer_id = ?
");
$stmt->execute([$order_id, $customer_id]);
$order = $stmt->fetch();

if (!$order) {
  die("Pesanan tidak ditemukan.");
}

/* =========================
   AMBIL ITEM PESANAN
========================= */
$stmtItems = $pdo->prepare("
  SELECT 
    oi.qty,
    oi.price,
    oi.subtotal,
    p.name
  FROM order_items oi
  JOIN products p ON oi.product_id = p.product_id
  WHERE oi.order_id = ?
");
$stmtItems->execute([$order_id]);
$items = $stmtItems->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Pesanan — Bali Heritage Wear</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="icon" href="../assets/img/logo.png" type="image/png">
</head>
<body>

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

    <div class="bg-white p-4 rounded shadow-sm mb-4">
      <h5 class="fw-bold mb-3">Informasi Pesanan</h5>

      <p>
        <strong>Kode Pesanan:</strong>
        <span class="badge bg-primary">
          <?= htmlspecialchars($order['order_code']) ?>
        </span>
      </p>

      <p><strong>Tanggal:</strong>
        <?= date('d M Y H:i', strtotime($order['created_at'])) ?>
      </p>

      <p><strong>Status:</strong>
        <span class="badge bg-secondary">
          <?= ucfirst($order['status']) ?>
        </span>
      </p>

      <p><strong>Metode Pembayaran:</strong>
        <?= strtoupper($order['payment_method']) ?>
      </p>

      <p><strong>Alamat Pengiriman:</strong><br>
        <?= nl2br(htmlspecialchars($order['shipping_address'])) ?><br>
        <?= htmlspecialchars($order['city']) ?>
      </p>

      <?php if ($order['payment_proof']): ?>
        <p><strong>Bukti Transfer:</strong><br>
          <a href="../<?= $order['payment_proof'] ?>" target="_blank">
            <img src="../<?= $order['payment_proof'] ?>" width="180" class="img-thumbnail mt-2">
          </a>
        </p>
      <?php endif; ?>

      <?php if ($order['note']): ?>
        <p><strong>Catatan:</strong><br>
          <?= nl2br(htmlspecialchars($order['note'])) ?>
        </p>
      <?php endif; ?>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
      <h5 class="fw-bold mb-3">Detail Produk</h5>

      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>Produk</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $item): ?>
            <tr>
              <td><?= htmlspecialchars($item['name']) ?></td>
              <td>Rp<?= number_format($item['price'], 0, ',', '.') ?></td>
              <td><?= $item['qty'] ?></td>
              <td class="fw-semibold">
                Rp<?= number_format($item['subtotal'], 0, ',', '.') ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <hr>

      <p class="text-end mb-1">
        Subtotal: <strong>Rp<?= number_format($order['subtotal'], 0, ',', '.') ?></strong>
      </p>
      <p class="text-end mb-1">
        Ongkir: <strong>Rp<?= number_format($order['shipping_cost'], 0, ',', '.') ?></strong>
      </p>
      <h5 class="text-end fw-bold text-brown">
        Total: Rp<?= number_format($order['total'], 0, ',', '.') ?>
      </h5>
    </div>

    <a href="orders.php" class="btn btn-secondary mt-4">
      ← Kembali ke Riwayat Pesanan
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
