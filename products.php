<?php
session_start();
require 'db.php';

// Handle 'add to cart' posted form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $pid = intval($_POST['product_id']);
    $qty = max(1, intval($_POST['quantity']));
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    // increment quantity if exists
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid] += $qty;
    } else {
        $_SESSION['cart'][$pid] = $qty;
    }
    header('Location: products.php?added=1');
    exit;
}

// Initial load: fetch only the first 6 products
$per_row = 6;
$offset = 0;

$res = $mysqli->query("SELECT id, name, description, price, image FROM products ORDER BY id ASC LIMIT $per_row OFFSET $offset");
$products = [];
if ($res) {
    while ($row = $res->fetch_assoc()) $products[] = $row;
    $res->free();
}

$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $cqty) $cart_count += $cqty;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Compet Store - Products</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3dafe 100%);
      min-height: 100vh;
      margin: 0;
      font-family: 'Segoe UI', 'Arial', sans-serif;
    }
    .container {
      max-width: 1300px;
      margin: 0 auto;
      padding: 2rem 1rem 1rem 1rem;
    }
    .modern-header {
      background: #fff;
      color: #2a3d66;
      box-shadow: 0 2px 12px rgba(44,62,80,0.07);
      padding: 0.7rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #e5e7eb;
    }
    .logo-area {
      display: flex;
      align-items: center;
      gap: 0.8rem;
    }
    .header-img {
      width: 48px;
      height: 48px;
      object-fit: cover;
      border-radius: 10px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.07);
      background: #f7f7f7;
      border: 1px solid #eee;
    }
    .logo-text {
      font-size: 1.7rem;
      font-weight: bold;
      letter-spacing: 1px;
      color: #2a3d66;
    }
    .main-nav ul {
      list-style: none;
      margin: 0;
      padding: 0;
      display: flex;
      gap: 1.5rem;
    }
    .main-nav li {
      display: inline;
    }
    .main-nav a {
      color: #2a3d66;
      text-decoration: none;
      font-size: 1.1rem;
      padding: 0.4rem 1.1rem;
      border-radius: 4px;
      transition: background 0.18s, color 0.18s;
      position: relative;
      font-weight: 500;
    }
    .main-nav a.active,
    .main-nav a:hover {
      background: #2a3d66;
      color: #fff;
    }
    .main-nav .cart-link::before {
      content: "🛒";
      margin-right: 0.4em;
    }
    .product-grid-responsive {
      display: grid;
      grid-template-columns: repeat(2, 1fr); /* 2 products per row */
      gap: 1.5rem;
      margin-bottom: 2rem;
    }
    @media (max-width: 1400px) {
      .product-grid-responsive {
        grid-template-columns: repeat(4, 1fr);
      }
    }
    @media (max-width: 1100px) {
      .product-grid-responsive {
        grid-template-columns: repeat(3, 1fr);
      }
    }
    @media (max-width: 800px) {
      .product-grid-responsive {
        grid-template-columns: 1fr;
      }
    }
    @media (max-width: 500px) {
      .product-grid-responsive {
        grid-template-columns: 1fr;
      }
    }
    .product-card {
      background: #fff;
      border-radius: 14px;
      border: 1px solid #e5e7eb;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      padding: 1.5rem 1.2rem 1.2rem 1.2rem;
      text-align: center;
      transition: box-shadow 0.2s, transform 0.2s;
      position: relative;
      overflow: hidden;
    }
    .product-card:hover {
      box-shadow: 0 8px 24px rgba(0,0,0,0.13);
      transform: translateY(-6px) scale(1.03);
    }
    .product-card img {
      width: 140px;
      height: 140px;
      object-fit: contain;
      margin-bottom: 1.1rem;
      display: block;
      margin-left: auto;
      margin-right: auto;
    }
    .product-card h3 {
      font-size: 1.18rem;
      margin: 0.3rem 0 0.5rem 0;
      color: #2a3d66;
      min-height: 2.4em;
    }
    .product-card .price {
      font-size: 1.25rem;
      color: #e67e22;
      font-weight: bold;
      margin-bottom: 0.5rem;
      letter-spacing: 0.5px;
    }
    .product-card .desc {
      color: #666;
      font-size: 0.98rem;
      min-height: 2.2em;
      margin-bottom: 1.1rem;
    }
    .add-form {
      margin-top: 0.7rem;
      opacity: 1;
      pointer-events: auto;
      transition: opacity 0.2s;
    }
    .add-form label {
      font-size: 0.98rem;
      margin-right: 0.5rem;
    }
    .add-form input[type="number"] {
      width: 55px;
      padding: 0.2rem 0.3rem;
      border-radius: 4px;
      border: 1px solid #ccc;
      margin-left: 0.2rem;
      margin-right: 0.7rem;
    }
    .add-form button {
      background: #2a3d66;
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 0.45rem 1.1rem;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.18s;
    }
    .add-form button:hover {
      background: #1d2847;
    }
    .footer {
      margin-top: 3rem;
      padding: 1.2rem 0;
      background: #f5f7fa;
      text-align: center;
      color: #888;
      font-size: 1rem;
      border-top: 1px solid #e0e0e0;
    }
    .loading-indicator {
      text-align: center;
      color: #2a3d66;
      font-size: 1.2rem;
      margin-bottom: 2rem;
      display: none;
    }
    @media (max-width: 600px) {
      .product-card img {
        width: 90px;
        height: 90px;
      }
      .product-card {
        padding: 1rem 0.5rem;
      }
    }
    body, html {
      overflow-x: hidden;
    }
  </style>
</head>
<body>
<?php
require 'header.php'; // Include the header with navigation
?>
<main class="container">
  <?php if (isset($_GET['added'])): ?>
    <p class="notice">Product added to cart.</p>
  <?php endif; ?>

  <div class="product-grid-responsive" id="product-grid">
    <?php foreach ($products as $p): ?>
      <div class="product-card">
        <img src="images/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
        <h3><?php echo htmlspecialchars($p['name']); ?></h3>
        <p class="price">₹<?php echo number_format($p['price'],2); ?></p>
        <p class="desc"><?php echo htmlspecialchars($p['description']); ?></p>
        <form method="post" class="add-form">
          <input type="hidden" name="product_id" value="<?php echo intval($p['id']); ?>">
          <label>Qty: <input type="number" name="quantity" value="1" min="1"></label>
          <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="loading-indicator" id="loading-indicator">Loading...</div>
</main>
<footer class="footer">
  &copy; <?php echo date('Y'); ?> Compet Store. All rights reserved.
</footer>
<script>
let offset = <?php echo $per_row; ?>;
const perRow = <?php echo $per_row; ?>;
let loading = false;

function createProductCard(product) {
  return `
    <div class="product-card">
      <img src="images/${product.image.replace(/"/g, '&quot;')}" alt="${product.name.replace(/"/g, '&quot;')}">
      <h3>${product.name.replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</h3>
      <p class="price">₹${parseFloat(product.price).toFixed(2)}</p>
      <p class="desc">${product.description.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</p>
      <form method="post" class="add-form">
        <input type="hidden" name="product_id" value="${product.id}">
        <label>Qty: <input type="number" name="quantity" value="1" min="1"></label>
        <button type="submit" name="add_to_cart">Add to Cart</button>
      </form>
    </div>
  `;
}

async function loadMoreProducts() {
  if (loading) return;
  loading = true;
  document.getElementById('loading-indicator').style.display = 'block';
  try {
    const response = await fetch('products_load.php?offset=' + offset + '&limit=' + perRow);
    if (!response.ok) return;
    const data = await response.json();
    if (data && data.length > 0) {
      const grid = document.getElementById('product-grid');
      data.forEach(product => {
        grid.insertAdjacentHTML('beforeend', createProductCard(product));
      });
      offset += perRow;
      loading = false;
      document.getElementById('loading-indicator').style.display = 'none';
      if (data.length < perRow) {
        window.removeEventListener('scroll', handleScroll);
      }
    } else {
      window.removeEventListener('scroll', handleScroll);
      document.getElementById('loading-indicator').style.display = 'none';
    }
  } catch (e) {
    document.getElementById('loading-indicator').style.display = 'none';
    loading = false;
  }
}

function handleScroll() {
  const scrollY = window.scrollY || window.pageYOffset;
  const viewportHeight = window.innerHeight;
  const fullHeight = document.body.offsetHeight;
  if (scrollY + viewportHeight + 100 >= fullHeight) {
    loadMoreProducts();
  }
}

window.addEventListener('scroll', handleScroll);
</script>
</body>
</html>
