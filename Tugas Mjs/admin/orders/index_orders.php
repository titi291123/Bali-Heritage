<?php
// admin/orders/index.php
$title = 'Kelola Pesanan';

require __DIR__ . '/../db.php';
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';

$orders = $pdo->query("
  SELECT o.*, c.name AS customer_name
  FROM orders o
  JOIN customers c ON o.customer_id = c.customer_id
  ORDER BY o.created_at DESC
")->fetchAll();
?>

<div class="admin-content">

  <!-- HEADER -->
  <div class="admin-card p-4 mb-4">
    <h4 class="fw-bold mb-1">Kelola Pesanan</h4>
    <p class="text-muted mb-0">
      Daftar seluruh pesanan yang dilakukan oleh customer.
    </p>
  </div>

  <!-- TABEL PESANAN -->
  <div class="admin-card p-0">
    <div class="table-responsive">
      <table class="table table-striped align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Pemesan</th>
            <th>Total</th>
            <th>Pembayaran</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($orders)): ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-4">
                Belum ada pesanan
              </td>
            </tr>
          <?php endif; ?>

          <?php foreach ($orders as $o): ?>
          <tr>
            <td>#<?= $o['order_id'] ?></td>
            <td><?= htmlspecialchars($o['customer_name']) ?></td>
            <td>
              <strong>
                Rp<?= number_format($o['total'], 0, ',', '.') ?>
              </strong>
            </td>
            <td><?= strtoupper($o['payment_method']) ?></td>
            <td>
              <span class="badge bg-secondary text-capitalize">
                <?= $o['status'] ?>
              </span>
            </td>
            <td>
              <?= date('d/m/Y H:i', strtotime($o['created_at'])) ?>
            </td>
            <td class="text-center">
              <a href="detail.php?id=<?= $o['order_id'] ?>"
                 class="btn btn-sm btn-primary">
                Detail
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
