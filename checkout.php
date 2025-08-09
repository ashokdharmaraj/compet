<?php
session_start();
require 'db.php';

// Collect cart items for review
$cart_items = [];
$total = 0.0;
if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $ids_list = implode(',', array_map('intval', $ids));
    $res = $mysqli->query("SELECT id, name, price FROM products WHERE id IN ($ids_list)");
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

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    // In production: validate inputs, save order to DB, send emails, integrate payment gateway
    $name = substr($_POST['name'] ?? '', 0, 200);
    $email = substr($_POST['email'] ?? '', 0, 200);
    $address = substr($_POST['address'] ?? '', 0, 1000);
    // For demo, we clear the cart and show success
    $_SESSION['cart'] = [];
    $success = true;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Pets Shop - Checkout</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php
require 'header.php'; // Include the header with navigation
?>

<main class="container">
  <h1>Checkout</h1>
  <?php if ($success): ?>
    <p class="notice success">Thank you! Your order has been placed (demo).</p>
    <p><a href="products.php">Continue shopping</a></p>
  <?php elseif (empty($cart_items)): ?>
    <p>Your cart is empty. <a href="products.php">Shop now</a></p>
  <?php else: ?>
    <h3>Order Summary</h3>
    <ul>
      <?php foreach ($cart_items as $it): ?>
        <li><?php echo htmlspecialchars($it['name']); ?> — <?php echo intval($it['quantity']); ?> × ₹<?php echo number_format($it['price'],2); ?> = ₹<?php echo number_format($it['line_total'],2); ?></li>
      <?php endforeach; ?>
    </ul>
    <p><strong>Total: ₹<?php echo number_format($total,2); ?></strong></p>
    <h3>Customer Details</h3>
    <form method="post">
      <label>Name<br><input type="text" name="name" required></label><br>
      <label>Email<br><input type="email" name="email" required></label><br>
      <label>Address<br><textarea name="address" rows="4" required></textarea></label><br>
      <button type="submit" name="place_order">Place Order (demo)</button>
    </form>
  <?php endif; ?>
</main>
</body>
</html>
