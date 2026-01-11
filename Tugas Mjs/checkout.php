<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: auth/login.php");
  exit;
}

require __DIR__ . '/admin/db.php';

$customer_id = $_SESSION['customer_id'];
$stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = ?");
$stmt->execute([$customer_id]);
$customer = $stmt->fetch();

if (!$customer) {
  echo "Data customer tidak ditemukan.";
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout — Bali Heritage Wear</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="icon" href="assets/img/logo.png" type="image/png">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm" id="mainNav">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="index.html">
        <img src="assets/img/logo.png" alt="logo" width="44" height="44" class="rounded">
        <span class="brand-title">Bali Heritage Wear</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item"><a class="nav-link" href="index.html">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="informasi.html">Informasi</a></li>
          <li class="nav-item"><a class="nav-link" href="product.html">Produk</a></li>
          <li class="nav-item">
            <a class="nav-link active" href="cart.html">
              <i class="bi bi-cart3 me-1"></i>Keranjang
              <span id="cart-count" class="badge bg-danger ms-1">0</span>
            </a>
          </li>
          <li class="nav-item"><a class="nav-link" href="account/index.php">Akun Saya</a></li>
        </ul>
      </div>
    </div>
  </nav>

<section class="py-5 bg-soft">
  <div class="container">
    <h3 class="fw-bold text-center mb-4">Checkout</h3>
    <?php if (!empty($_SESSION['checkout_error'])): ?>
  <div class="alert alert-danger">
    <?= htmlspecialchars($_SESSION['checkout_error']) ?>
  </div>
  <?php unset($_SESSION['checkout_error']); ?>
<?php endif; ?>


    <div class="row g-4">

      <!-- FORM -->
      <div class="col-md-6">
        <form action="process-order.php" method="post" enctype="multipart/form-data"
              class="bg-white p-4 rounded shadow-sm">

          <h5 class="fw-bold mb-3">Data Pemesan</h5>

          <p><strong>Nama:</strong> <?= htmlspecialchars($customer['name']) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?></p>
          <p><strong>WhatsApp:</strong> <?= htmlspecialchars($customer['whatsapp']) ?></p>

          <hr>

          <div class="mb-3">
            <label class="form-label fw-semibold">Alamat Lengkap Pengiriman</label>
            <textarea name="shipping_address" class="form-control" rows="4" required><?= htmlspecialchars((string)$customer['address']) ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Kota / Kabupaten</label>
            <select name="city" id="city" class="form-select" required>
              <option value="">-- Pilih Kota --</option>
              <option value="Gianyar" data-ongkir="15000">Gianyar (Rp15.000)</option>
              <option value="Denpasar" data-ongkir="20000">Denpasar (Rp20.000)</option>
              <option value="Badung" data-ongkir="25000">Badung (Rp25.000)</option>
            </select>
          </div>

          <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">

          <div class="mb-3">
            <label class="form-label fw-semibold">Metode Pembayaran</label>
            <select name="payment_method" id="payment_method" class="form-select" required>
              <option value="">-- Pilih Metode --</option>
              <option value="transfer">Transfer Bank</option>
              <option value="cod">COD</option>
            </select>
          </div>

          <div class="mb-3 d-none" id="buktiTransfer">
            <label class="form-label fw-semibold">Upload Bukti Transfer - BRI 4642010011001100 A/N Ketut Jemba</label>
            <input type="file" name="payment_proof" class="form-control" accept="image/*">
          </div>

          <div class="mb-3">
            <label class="form-label">Catatan Tambahan</label>
            <textarea name="note" class="form-control" rows="2"></textarea>
          </div>
          
          <input type="hidden" name="cart_json" id="cart_json">
          <button type="submit" class="btn btn-primary w-100 fw-semibold">
            Buat Pesanan
          </button>
        </form>
      </div>

      <!-- RINGKASAN -->
      <div class="col-md-6">
        <div class="bg-white p-4 rounded shadow-sm">
          <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>

          <table class="table" id="checkout-summary">
            <tbody></tbody>
          </table>

          <hr>

          <p class="text-end">Subtotal: <span id="subtotalText">Rp0</span></p>
          <p class="text-end">Ongkir: <span id="ongkirText">Rp0</span></p>
          <h5 class="text-end fw-bold text-brown">
            Total: <span id="checkout-total">Rp0</span>
          </h5>
        </div>
      </div>

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

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/cart.js"></script>
  <script>
    updateCartBadge();
  </script>

<script src="assets/js/cart.js"></script>
<script>
  // PAKSA hitung ulang total setelah checkout summary selesai dirender
  async function refreshCheckoutTotal() {
    await renderCheckoutSummary();

    let subtotal = 0;
    document.querySelectorAll('#checkout-summary tbody tr').forEach(row => {
      const text = row.querySelector('td:last-child')?.innerText || '0';
      const angka = Number(text.replace(/[^\d]/g, '')) || 0;
      subtotal += angka;
    });

    const ongkir = Number(document.getElementById('shipping_cost').value || 0);

    document.getElementById('subtotalText').innerText =
      'Rp' + subtotal.toLocaleString('id-ID');
    document.getElementById('checkout-total').innerText =
      'Rp' + (subtotal + ongkir).toLocaleString('id-ID');
  }

  document.addEventListener('DOMContentLoaded', () => {
    refreshCheckoutTotal();
  });

  document.getElementById('city').addEventListener('change', function () {
    const ongkir = Number(this.selectedOptions[0].dataset.ongkir || 0);
    document.getElementById('shipping_cost').value = ongkir;
    document.getElementById('ongkirText').innerText =
      'Rp' + ongkir.toLocaleString('id-ID');

    refreshCheckoutTotal();
  });

  document.getElementById('payment_method').addEventListener('change', function () {
    document.getElementById('buktiTransfer')
      .classList.toggle('d-none', this.value !== 'transfer');
  });
</script>
<script>
  updateCartBadge();
  renderCheckoutSummary();

  const citySelect = document.getElementById('city');
  const shippingInput = document.getElementById('shipping_cost');

  citySelect.addEventListener('change', function () {
    checkoutOngkir = Number(this.selectedOptions[0].dataset.ongkir || 0);
    shippingInput.value = checkoutOngkir;
    updateCheckoutTotal();
  });

  const paymentMethod = document.getElementById('payment_method');
  const bukti = document.getElementById('buktiTransfer');

  paymentMethod.addEventListener('change', function () {
    bukti.classList.toggle('d-none', this.value !== 'transfer');
  });
</script>
<script>
document.querySelector('form').addEventListener('submit', function () {
  document.getElementById('cart_json').value =
    localStorage.getItem('bhw_cart_v1') || '[]';
});
</script>


</body>
</html>
