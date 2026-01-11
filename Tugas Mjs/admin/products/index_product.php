<?php
// admin/products/index.php
require __DIR__ . '/../db.php';

$title = 'Kelola Produk';

$products = $pdo->query("
  SELECT p.*, c.name AS category_name
  FROM products p
  LEFT JOIN categories c ON p.category_id = c.category_id
  ORDER BY p.created_at DESC
")->fetchAll();

include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="admin-content">

  <!-- Header -->
  <div class="admin-card p-4 mb-4 d-flex justify-content-between align-items-center">
    <div>
      <h4 class="fw-bold mb-1">Kelola Produk</h4>
      <p class="text-muted mb-0">Daftar seluruh produk yang tampil di website.</p>
    </div>
    <a href="create.php" class="btn btn-primary">
      <i class="bi bi-plus-circle me-1"></i> Tambah Produk
    </a>
  </div>

  <!-- Table -->
  <div class="admin-card p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th width="60">ID</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Gambar</th>
            <th width="160">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($products) === 0): ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-4">
                Belum ada produk.
              </td>
            </tr>
          <?php endif; ?>

          <?php foreach ($products as $p): ?>
            <tr>
              <td><?= $p['product_id'] ?></td>

              <td>
                <strong><?= htmlspecialchars($p['name']) ?></strong>
              </td>

              <td>
                <span class="badge bg-secondary">
                  <?= htmlspecialchars($p['category_name'] ?? '-') ?>
                </span>
              </td>

              <td>
                Rp <?= number_format($p['price'], 0, ',', '.') ?>
              </td>

              <td>
                <?= $p['stock'] ?>
              </td>

              <td>
                <?php if ($p['image_main']): ?>
                  <img
                    src="../../<?= htmlspecialchars($p['image_main']) ?>" width="80"
                    width="60"
                    height="60"
                    class="rounded object-fit-cover"
                    alt=""
                  >
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>

              <td>
                <div class="d-flex gap-2">
                  <a
                    href="edit.php?id=<?= $p['product_id'] ?>"
                    class="btn btn-sm btn-warning"
                  >
                    <i class="bi bi-pencil-square"></i>
                  </a>

                  <a
                    href="delete.php?id=<?= $p['product_id'] ?>"
                    class="btn btn-sm btn-danger"
                    onclick="return confirm('Yakin hapus produk ini?')"
                  >
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
