// === PENCARIAN PRODUK ===
const searchInput = document.getElementById('searchInput');
const productItems = document.querySelectorAll('.product-item');
const categoryLinks = document.querySelectorAll('.category-link');

if (searchInput) {
  searchInput.addEventListener('input', function () {
    const query = this.value.toLowerCase();
    productItems.forEach(item => {
      const name = item.getAttribute('data-name').toLowerCase();
      item.style.display = name.includes(query) ? 'block' : 'none';
    });
  });
}

// === FILTER BERDASARKAN KATEGORI ===
categoryLinks.forEach(link => {
  link.addEventListener('click', (e) => {
    e.preventDefault();
    const filter = link.getAttribute('data-filter');

    // ubah tampilan link aktif
    categoryLinks.forEach(l => l.classList.remove('active'));
    link.classList.add('active');

    productItems.forEach(item => {
      if (filter === 'all' || item.getAttribute('data-category') === filter) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });
  });
});

// === UPDATE KERANJANG (ambil dari cart.js) ===
if (typeof updateCartBadge === 'function') {
  updateCartBadge();
}
