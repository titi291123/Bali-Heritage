<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="admin-sidebar p-3">
  <h5 class="fw-bold mb-4">
    🛍️ Admin<br>
    <small class="opacity-75">Bali Heritage Wear</small>
  </h5>

  <nav class="d-grid gap-2">
    <a href="/bali-heritage-wear/admin/"
       class="<?= ($currentPage == 'index.php') ? 'active' : '' ?>">
      <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>

    <a href="/bali-heritage-wear/admin/products/index_product.php"
       class="<?= ($currentPage == 'index_product.php' && strpos($_SERVER['REQUEST_URI'], 'products') !== false) ? 'active' : '' ?>">
      <i class="bi bi-box-seam me-2"></i> Produk
    </a>

    <a href="/bali-heritage-wear/admin/products/create.php"
       class="<?= ($currentPage == 'create.php') ? 'active' : '' ?>">
      <i class="bi bi-plus-circle me-2"></i> Tambah Produk
    </a>

    <a href="/bali-heritage-wear/admin/orders/index_orders.php"
       class="<?= ($currentPage == 'index_orders.php') ? 'active' : '' ?>">
      <i class="bi bi-receipt me-2"></i> Kelola Pesanan
    </a>

    <hr class="border-light opacity-25">

    <a href="/bali-heritage-wear/"
       class="<?= (strpos($_SERVER['REQUEST_URI'], '/bali-heritage-wear/') !== false && strpos($_SERVER['REQUEST_URI'], '/admin') === false) ? 'active' : '' ?>">
      <i class="bi bi-globe me-2"></i> Lihat Website
    </a>

    <hr>
    <a href="/bali-heritage-wear/admin/auth/logout.php"
      class="btn btn-outline-danger w-100">
      Logout
    </a>

  </nav>
</div>
