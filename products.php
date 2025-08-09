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

// Fetch products from DB
$res = $mysqli->query("SELECT id, name, description, price, image FROM products ORDER BY id ASC");
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
  <title>Pets Shop - Products</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="site-header">
  <div class="logo">Pets Shop</div>
  <nav>
    <a href="products.php">Products</a>
    <a href="cart.php">Cart (<?php echo $cart_count; ?>)</a>
  </nav>
</header>
<main class="container">
  <h1>Product Catalog</h1>
  <?php if (isset($_GET['added'])): ?>
    <p class="notice">Product added to cart.</p>
  <?php endif; ?>
  <div class="product-grid">
    <?php foreach ($products as $p): ?>
      <div class="product-card">
        <img src="images/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
        <h3><?php echo htmlspecialchars($p['name']); ?></h3>
        <p class="price">₹<?php echo number_format($p['price'],2); ?></p>
        <p class="desc"><?php echo htmlspecialchars($p['description']); ?></p>
        <form method="post" class="add-form">
          <input type="hidden" name="product_id" value="<?php echo intval($p['id']); ?>">
          <label>Qty: <input type="number" name="quantity" value="1" min="1" style="width:60px"></label>
          <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</main>
</body>
</html>
