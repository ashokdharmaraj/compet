<?php
session_start();
require 'db.php';

// Remove product
if (isset($_GET['remove'])) {
    $rid = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$rid])) {
        unset($_SESSION['cart'][$rid]);
    }
    header('Location: cart.php');
    exit;
}

// Update quantities
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    foreach ($_POST['qty'] as $pid => $q) {
        $pid = intval($pid);
        $q = max(0, intval($q));
        if ($q == 0) {
            unset($_SESSION['cart'][$pid]);
        } else {
            $_SESSION['cart'][$pid] = $q;
        }
    }
    header('Location: cart.php');
    exit;
}

// Fetch product details for items in cart
$cart_items = [];
$total = 0.0;
if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $ids_list = implode(',', array_map('intval', $ids));
    $res = $mysqli->query("SELECT id, name, price, image FROM products WHERE id IN ($ids_list)");
    while ($row = $res->fetch_assoc()) {
        $pid = $row['id'];
        $qty = $_SESSION['cart'][$pid];
        $line = $row;
        $line['quantity'] = $qty;
        $line['line_total'] = $row['price'] * $qty;
        $total += $line['line_total'];
        $cart_items[] = $line;
    }
    if ($res) $res->free();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Pets Shop - Cart</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="site-header">
  <div class="logo">Pets Shop</div>
  <nav>
    <a href="products.php">Products</a>
    <a href="cart.php">Cart</a>
  </nav>
</header>
<main class="container">
  <h1>Your Cart</h1>
  <?php if (empty($cart_items)): ?>
    <p>Your cart is empty. <a href="products.php">Shop now</a></p>
  <?php else: ?>
    <form method="post">
      <input type="hidden" name="update_cart" value="1">
      <div class="cart-list">
        <?php foreach ($cart_items as $item): ?>
          <div class="cart-item">
            <div>
              <img src="images/<?php echo htmlspecialchars($item['image']); ?>" alt="" class="mini">
              <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
              ₹<?php echo number_format($item['price'],2); ?> x 
              <input type="number" name="qty[<?php echo intval($item['id']); ?>]" value="<?php echo intval($item['quantity']); ?>" min="0" style="width:60px">
              = ₹<?php echo number_format($item['line_total'],2); ?>
            </div>
            <div>
              <a class="btn" href="cart.php?remove=<?php echo intval($item['id']); ?>">Remove</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="cart-summary">
        <h3>Total: ₹<?php echo number_format($total,2); ?></h3>
        <div class="cart-actions">
          <button type="submit">Update Cart</button>
          <a class="btn" href="checkout.php">Proceed to Checkout</a>
        </div>
      </div>
    </form>
  <?php endif; ?>
</main>
</body>
</html>
