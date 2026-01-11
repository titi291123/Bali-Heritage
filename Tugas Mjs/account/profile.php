<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

require __DIR__ . '/../admin/db.php';

$customer_id = $_SESSION['customer_id'];

$stmt = $pdo->prepare("SELECT name, email, whatsapp FROM customers WHERE customer_id = ?");
$stmt->execute([$customer_id]);
$customer = $stmt->fetch();

if (!$customer) {
  die("Data customer tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profil Saya — Bali Heritage Wear</title>

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
  <div class="container" style="max-width:600px">

    <div class="bg-white p-4 rounded shadow-sm">
      <h5 class="fw-bold mb-3">Edit Profil</h5>

      <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Profil berhasil diperbarui.</div>
      <?php endif; ?>

      <form action="profile-update.php" method="post">

        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="name" class="form-control"
                 value="<?= htmlspecialchars($customer['name']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control"
                 value="<?= htmlspecialchars($customer['email']) ?>" disabled>
          <small class="text-muted">Email tidak dapat diubah</small>
        </div>

        <div class="mb-3">
          <label class="form-label">Nomor WhatsApp</label>
          <input type="text" name="whatsapp" class="form-control"
                 value="<?= htmlspecialchars($customer['whatsapp']) ?>" required>
        </div>

        <hr>

        <h6 class="fw-bold">Ganti Password (Opsional)</h6>

        <div class="mb-3">
          <label class="form-label">Password Baru</label>
          <input type="password" name="new_password" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Konfirmasi Password Baru</label>
          <input type="password" name="confirm_password" class="form-control">
        </div>

        <button class="btn btn-primary w-100">
          Simpan Perubahan
        </button>

      </form>
    </div>

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
