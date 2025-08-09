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
  <title>Compet Store - Cart</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php
require 'header.php'; // Include the header with navigation
?>
<style>
  .modern-header {
    background: #1a237e;
    color: #fff;
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
    color: #fff;
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
    color: #ffe082; /* Yellow for contrast */
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
    background: #fff;
    color: #1a237e;
  }
  .main-nav .cart-link::before {
    content: "🛒";
    margin-right: 0.4em;
  }
</style>
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
