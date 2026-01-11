<?php
require __DIR__ . '/middleware.php';
// admin/index.php
$title = 'Dashboard Admin';
include __DIR__ . '/layout/header.php';
include __DIR__ . '/layout/sidebar.php';
?>

<div class="admin-content">
  <div class="admin-card p-4 mb-4">
    <h4 class="fw-bold mb-2">Dashboard Admin</h4>
    <p class="text-muted mb-0">
      Selamat datang di panel admin <strong>Bali Heritage Wear</strong>.
      Gunakan menu di samping untuk mengelola data.
    </p>
  </div>

  <div class="row g-4">
    <!-- Kelola Produk -->
    <div class="col-md-4">
      <div class="admin-card p-4 text-center h-100">
        <i class="bi bi-box-seam fs-1 text-brown mb-3"></i>
        <h5 class="fw-bold">Kelola Produk</h5>
        <p class="text-muted small">
          Tambah, edit, dan hapus produk yang tampil di website.
        </p>
        <a href="products/index_product.php" class="btn btn-primary w-100">
          Masuk
        </a>
      </div>
    </div>

    <!-- Tambah Produk -->
    <div class="col-md-4">
      <div class="admin-card p-4 text-center h-100">
        <i class="bi bi-plus-circle fs-1 text-success mb-3"></i>
        <h5 class="fw-bold">Tambah Produk</h5>
        <p class="text-muted small">
          Tambahkan produk baru lengkap dengan gambar dan harga.
        </p>
        <a href="products/create.php" class="btn btn-success w-100">
          Tambah
        </a>
      </div>
    </div>

    <!-- Kelola Pesanan -->
    <div class="col-md-4">
      <div class="admin-card p-4 text-center h-100">
        <i class="bi bi-receipt fs-1 text-primary mb-3"></i>
        <h5 class="fw-bold">Kelola Pesanan</h5>
        <p class="text-muted small">
          Lihat pesanan customer, detail produk, dan status pembayaran.
        </p>
        <a href="orders/index_orders.php" class="btn btn-primary w-100">
          Lihat Pesanan
        </a>
      </div>
    </div>

    

    <!-- Lihat Website -->
    <div class="col-md-4">
      <div class="admin-card p-4 text-center h-100">
        <i class="bi bi-globe fs-1 text-secondary mb-3"></i>
        <h5 class="fw-bold">Lihat Website</h5>
        <p class="text-muted small">
          Kembali ke halaman utama Bali Heritage Wear.
        </p>
        <a href="../" class="btn btn-outline-secondary w-100">
          Kunjungi
        </a>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
