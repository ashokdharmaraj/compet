<?php
session_start();
require 'db.php';
// You should check if the user is logged in here

// Example: Fetch orders for the logged-in user
$user_id = $_SESSION['user_id'] ?? 0;
$orders = [];
if ($user_id) {
    $res = $mysqli->query("SELECT id, order_date, total FROM orders WHERE user_id = $user_id ORDER BY order_date DESC");
    if ($res) {
        while ($row = $res->fetch_assoc()) $orders[] = $row;
        $res->free();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>My Orders - Compet Store</title>
  <style>
    body { background: linear-gradient(135deg, #f5f7fa 0%, #c3dafe 100%); min-height: 100vh; margin: 0; font-family: 'Segoe UI', 'Arial', sans-serif; }
    .container { max-width: 900px; margin: 2rem auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(44,62,80,0.07); padding: 2rem; }
    h2 { color: #1a237e; margin-bottom: 1.5rem; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 0.8rem; border-bottom: 1px solid #e0e0e0; text-align: left; }
    th { background: #e3f0ff; color: #1a237e; }
    tr:last-child td { border-bottom: none; }
    .no-orders { text-align: center; color: #888; margin: 2rem 0; }
  </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
  <h2>My Orders</h2>
  <?php if (empty($orders)): ?>
    <div class="no-orders">You have no orders yet.</div>
  <?php else: ?>
    <table>
      <tr>
        <th>Order #</th>
        <th>Date</th>
        <th>Total</th>
        <th>Details</th>
      </tr>
      <?php foreach ($orders as $order): ?>
      <tr>
        <td><?php echo htmlspecialchars($order['id']); ?></td>
        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
        <td>₹<?php echo number_format($order['total'],2); ?></td>
        <td><a href="order_details.php?id=<?php echo $order['id']; ?>">View</a></td>
      </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</div>
</body>
</html>