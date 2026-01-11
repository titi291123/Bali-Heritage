<?php
// admin/products/edit.php
require __DIR__ . '/../db.php';

$title = 'Edit Produk';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
  header("Location: index.php");
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ? LIMIT 1");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) {
  echo "Produk tidak ditemukan";
  exit;
}

$cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="admin-content">

  <!-- Header -->
  <div class="admin-card p-4 mb-4">
    <h4 class="fw-bold mb-1">Edit Produk</h4>
    <p class="text-muted mb-0">
      Perbarui informasi produk yang ditampilkan di website.
    </p>
  </div>

  <!-- Form -->
  <div class="admin-card p-4">
    <form action="update.php" method="post" enctype="multipart/form-data" class="row g-4">

      <input type="hidden" name="id" value="<?= $p['product_id'] ?>">

      <div class="col-md-6">
        <label class="form-label fw-semibold">Nama Produk</label>
        <input
          type="text"
          name="name"
          class="form-control"
          required
          value="<?= htmlspecialchars($p['name']) ?>"
        >
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Kategori</label>
        <select name="category_id" class="form-select">
          <option value="">-- Pilih Kategori --</option>
          <?php foreach ($cats as $c): ?>
            <option
              value="<?= $c['category_id'] ?>"
              <?= $c['category_id'] == $p['category_id'] ? 'selected' : '' ?>
            >
              <?= htmlspecialchars($c['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Harga (Rp)</label>
        <input
          type="number"
          name="price"
          class="form-control"
          required
          value="<?= $p['price'] ?>"
        >
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Harga Asli (Diskon)</label>
        <input
          type="number"
          name="discount_price"
          class="form-control"
          value="<?= $p['discount_price'] ?>"
        >
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Stok</label>
        <input
          type="number"
          name="stock"
          class="form-control"
          value="<?= $p['stock'] ?>"
        >
      </div>

      <div class="col-12">
        <label class="form-label fw-semibold">Deskripsi Singkat</label>
        <input
          type="text"
          name="short_description"
          class="form-control"
          value="<?= htmlspecialchars($p['short_description']) ?>"
        >
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">
          Gambar Produk
          <span class="text-muted">(kosongkan jika tidak diganti)</span>
        </label>
        <input
          type="file"
          name="image"
          class="form-control"
          accept="image/*"
        >

        <?php if ($p['image_main']): ?>
          <div class="mt-3">
            <p class="mb-1 text-muted">Gambar saat ini:</p>
            <img
              src="../../<?= htmlspecialchars($p['image_main']) ?>" width="80"
              width="160"
              height="160"
              class="rounded border object-fit-cover"
              alt="Preview"
            >
          </div>
        <?php endif; ?>
      </div>

      <div class="col-12 d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-save me-1"></i> Update Produk
        </button>
        <a href="index_product.php" class="btn btn-outline-secondary">
          Kembali
        </a>
      </div>

    </form>
  </div>

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
