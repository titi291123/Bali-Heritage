<?php
// admin/products/create.php
require __DIR__ . '/../db.php';
$title = 'Tambah Produk';

$cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="admin-content">

  <div class="admin-card p-4 mb-4">
    <h4 class="fw-bold mb-1">Tambah Produk</h4>
    <p class="text-muted mb-0">Isi data produk dengan lengkap dan benar.</p>
  </div>

  <div class="admin-card p-4">
    <form action="store.php" method="post" enctype="multipart/form-data" class="row g-3">

      <div class="col-md-6">
        <label class="form-label fw-semibold">Nama Produk</label>
        <input name="name" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Kategori</label>
        <select name="category_id" class="form-select">
          <option value="">-- Pilih Kategori --</option>
          <?php foreach ($cats as $c): ?>
            <option value="<?= $c['category_id'] ?>">
              <?= htmlspecialchars($c['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Harga (Rp)</label>
        <input name="price" type="number" class="form-control" required>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Harga Asli (Diskon)</label>
        <input name="discount_price" type="number" class="form-control">
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Stok</label>
        <input name="stock" type="number" class="form-control" value="0">
      </div>

      <div class="col-12">
        <label class="form-label fw-semibold">Deskripsi Singkat</label>
        <input name="short_description" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Gambar Produk</label>
        <input
          name="image"
          type="file"
          accept="image/*"
          class="form-control"
          required
        >
      </div>

      <div class="col-12 d-flex gap-2 mt-3">
        <button class="btn btn-primary">
          <i class="bi bi-save me-1"></i> Simpan Produk
        </button>
        <a href="index_product.php" class="btn btn-outline-secondary">
          Batal
        </a>
      </div>

    </form>
  </div>

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
