<?php
// admin/orders/detail.php
$title = 'Detail Pesanan';

require __DIR__ . '/../db.php';
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) die("Order tidak valid");

$stmt = $pdo->prepare("
  SELECT o.*, c.name, c.email, c.whatsapp
  FROM orders o
  JOIN customers c ON o.customer_id = c.customer_id
  WHERE o.order_id = ?
");
$stmt->execute([$id]);
$order = $stmt->fetch();
if (!$order) die("Pesanan tidak ditemukan");

$stmt = $pdo->prepare("
  SELECT oi.*, p.name
  FROM order_items oi
  JOIN products p ON oi.product_id = p.product_id
  WHERE oi.order_id = ?
");
$stmt->execute([$id]);
$items = $stmt->fetchAll();
?>

<div class="admin-content">

  <div class="admin-card p-4 mb-4">
    <h4 class="fw-bold mb-1">Detail Pesanan #<?= $order['order_id'] ?></h4>
    <p class="text-muted mb-0">
      Informasi lengkap pesanan customer
    </p>
  </div>

  <div class="row g-4">

    <!-- INFO CUSTOMER -->
    <div class="col-md-6">
      <div class="admin-card p-4 h-100">
        <h6 class="fw-bold mb-3">Data Pemesan</h6>

        <p><strong>Nama:</strong> <?= htmlspecialchars($order['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
        <p><strong>WhatsApp:</strong> <?= htmlspecialchars($order['whatsapp']) ?></p>

        <hr>

        <p><strong>Alamat Pengiriman:</strong><br>
          <?= nl2br(htmlspecialchars($order['shipping_address'])) ?>
        </p>
        <p><strong>Kota:</strong> <?= htmlspecialchars($order['city']) ?></p>
        <p><strong>Pembayaran:</strong> <?= strtoupper($order['payment_method']) ?></p>

        <?php if ($order['payment_proof']): ?>
          <hr>
          <p class="mb-2"><strong>Bukti Transfer:</strong></p>
         <img 
         src="../../<?= $order['payment_proof'] ?>" 
         class="img-thumbnail"
         width="220"
         style="cursor:pointer"
         data-bs-toggle="modal"
         data-bs-target="#buktiTransferModal"
         >
        <?php endif; ?>
      </div>
    </div>

    <!-- ITEM PESANAN -->
    <div class="col-md-6">
      <div class="admin-card p-4 h-100">
        <h6 class="fw-bold mb-3">Item Pesanan</h6>

        <ul class="list-group mb-3">
          <?php foreach ($items as $i): ?>
          <li class="list-group-item d-flex justify-content-between">
            <?= htmlspecialchars($i['name']) ?> x<?= $i['qty'] ?>
            <span>Rp<?= number_format($i['subtotal'],0,',','.') ?></span>
          </li>
          <?php endforeach; ?>
        </ul>

        <p><strong>Subtotal:</strong> Rp<?= number_format($order['subtotal'],0,',','.') ?></p>
        <p><strong>Ongkir:</strong> Rp<?= number_format($order['shipping_cost'],0,',','.') ?></p>

        <h5 class="fw-bold">
          Total: Rp<?= number_format($order['total'],0,',','.') ?>
        </h5>
      </div>
    </div>

  </div>

  <!-- UPDATE STATUS -->
  <div class="admin-card p-4 mt-4">
    <h6 class="fw-bold mb-3">Update Status Pesanan</h6>

    <form action="update-status.php" method="post">
      <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">

      <select name="status" class="form-select mb-3">
        <?php
        $statuses = ['pending','paid','processing','shipped','completed','cancelled'];
        foreach ($statuses as $s):
        ?>
          <option value="<?= $s ?>" <?= $order['status']===$s?'selected':'' ?>>
            <?= ucfirst($s) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <button class="btn btn-primary">Update Status</button>
      <a href="index_orders.php" class="btn btn-secondary ms-2">Kembali</a>
    </form>
  </div>
  <?php if ($order['payment_proof']): ?>
<!-- MODAL BUKTI TRANSFER -->
<div class="modal fade" id="buktiTransferModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Bukti Transfer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">
        <img 
          src="../../<?= $order['payment_proof'] ?>" 
          class="img-fluid rounded"
          alt="Bukti Transfer"
        >
      </div>

    </div>
  </div>
</div>
<?php endif; ?>


</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
