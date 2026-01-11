// ------- Simpan produk -------
const PRODUCTS = [
  { id: 1, name: "Udeng Batik Tulis", price: 150000, img: "assets/img/product1.png" },
  { id: 2, name: "Saput Songket Ungu", price: 180000, img: "assets/img/product2.jpg" },
  { id: 3, name: "Kemeja Putih", price: 200000, img: "assets/img/product3.jpeg" },
  { id: 4, name: "Selendang Songket", price: 120000, img: "assets/img/product4.jpg" },
  { id: 5, name: "Kebaya Bali Modern", price: 250000, img: "assets/img/product5.jpg" },
  { id: 6, name: "Udeng Batik", price: 120000, img: "assets/img/product6.jpg" },
  { id: 7, name: "Kebaya Kuning", price: 150000, img: "assets/img/product7.jpg" },
  { id: 8, name: "Kemeja Hitam", price: 180000, img: "assets/img/product8.jpg" },
  { id: 9, name: "Kamen Hitam", price: 150000, img: "assets/img/product9.jpg" }
];

// ------- Tambah ke keranjang -------
function addToCart(id) {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  const product = PRODUCTS.find(p => p.id === id);
  const existing = cart.find(p => p.id === id);
  if (existing) existing.qty += 1;
  else cart.push({ ...product, qty: 1 });
  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartBadge();
  alert("Produk ditambahkan ke keranjang!");
}

// ------- Render semua produk -------
function renderProductList() {
  const list = document.getElementById("product-list");
  if (!list) return;
  list.innerHTML = PRODUCTS.map(p => `
    <div class="col-md-4">
      <div class="card card-product h-100">
        <img src="${p.img}" class="card-img-top" alt="${p.name}">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">${p.name}</h5>
          <p class="fw-bold text-brown mb-3">Rp${p.price.toLocaleString()}</p>
          <div class="mt-auto d-grid">
            <button class="btn btn-primary" onclick="addToCart(${p.id})">Pesan</button>
          </div>
        </div>
      </div>
    </div>`).join("");
}

// ------- Render tabel keranjang -------
function renderCartTable() {
  const tbody = document.querySelector("#cart-table tbody");
  if (!tbody) return;
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  let total = 0;
  tbody.innerHTML = cart.map(item => {
    const subtotal = item.price * item.qty;
    total += subtotal;
    return `
      <tr>
        <td><img src="${item.img}" width="60" class="me-2 rounded"> ${item.name}</td>
        <td>Rp${item.price.toLocaleString()}</td>
        <td><input type="number" min="1" value="${item.qty}" onchange="updateQty(${item.id}, this.value)" class="form-control" style="width:80px"></td>
        <td>Rp${subtotal.toLocaleString()}</td>
        <td><button class="btn btn-sm btn-danger" onclick="removeFromCart(${item.id})"><i class="bi bi-x-lg"></i></button></td>
      </tr>`;
  }).join("");
  document.getElementById("cart-total").innerText = "Rp" + total.toLocaleString();
}

// ------- Update jumlah -------
function updateQty(id, qty) {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  cart = cart.map(item => item.id === id ? { ...item, qty: parseInt(qty) } : item);
  localStorage.setItem("cart", JSON.stringify(cart));
  renderCartTable();
  updateCartBadge();
}

// ------- Hapus item -------
function removeFromCart(id) {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  cart = cart.filter(item => item.id !== id);
  localStorage.setItem("cart", JSON.stringify(cart));
  renderCartTable();
  updateCartBadge();
}

// ------- Update badge -------
function updateCartBadge() {
  const count = JSON.parse(localStorage.getItem("cart"))?.reduce((a, b) => a + b.qty, 0) || 0;
  const badge = document.getElementById("cart-count");
  if (badge) badge.innerText = count;
}

// ------- Render ringkasan checkout -------
function renderCheckoutSummary() {
  const tbody = document.querySelector("#checkout-summary tbody");
  if (!tbody) return;
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  let total = 0;
  tbody.innerHTML = cart.map(item => {
    const subtotal = item.price * item.qty;
    total += subtotal;
    return `<tr><td>${item.name} x${item.qty}</td><td>Rp${subtotal.toLocaleString()}</td></tr>`;
  }).join("");
  document.getElementById("checkout-total").innerText = "Rp" + total.toLocaleString();
}

// ------- Kirim via WhatsApp -------
function sendWhatsAppOrder() {
  const name = document.getElementById("name").value;
  const whatsapp = document.getElementById("whatsapp").value;
  const address = document.getElementById("address").value;
  const note = document.getElementById("note").value;
  const cart = JSON.parse(localStorage.getItem("cart")) || [];

  if (!name || !whatsapp || !address) {
    alert("Lengkapi semua data terlebih dahulu!");
    return;
  }

  let message = `Halo Bali Heritage Wear,%0ASaya ingin memesan:%0A`;
  cart.forEach(item => message += `- ${item.name} x${item.qty}%0A`);
  message += `%0AAlamat: ${address}%0ANama: ${name}%0ANo. WA: ${whatsapp}%0ACatatan: ${note}`;
  const url = `https://wa.me/62881037557496?text=${message}`;
  window.open(url, "_blank");
}
