<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
require '../db.php';

$res = $mysqli->query("SELECT * FROM products ORDER BY id DESC");
$products = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin - Products</title>
<style>
body{font-family:Arial; max-width:900px; margin:2rem auto;}
table {border-collapse: collapse; width: 100%;}
th, td {border: 1px solid #ccc; padding: 8px; text-align: left;}
th {background: #0b8a6f; color:#fff;}
a.button {background:#0b8a6f; color:#fff; padding:0.4rem 0.8rem; text-decoration:none; border-radius:4px;}
a.button:hover {background:#066b54;}
img {max-width:80px; border-radius:4px;}
.top-bar {display:flex; justify-content: space-between; margin-bottom:1rem;}
</style>
</head>
<body>
<div class="top-bar">
  <h1>Manage Products</h1>
  <div>
    <a href="add_product.php" class="button">+ Add Product</a>
    <a href="logout.php" class="button" style="background:#a00; margin-left:1rem;">Logout</a>
  </div>
</div>
<table>
<thead>
<tr>
  <th>ID</th>
  <th>Image</th>
  <th>Name</th>
  <th>Description</th>
  <th>Price (₹)</th>
  <th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($products as $p): ?>
<tr>
  <td><?php echo intval($p['id']); ?></td>
  <td><img src="../images/<?php echo htmlspecialchars($p['image']); ?>" alt=""></td>
  <td><?php echo htmlspecialchars($p['name']); ?></td>
  <td><?php echo htmlspecialchars($p['description']); ?></td>
  <td><?php echo number_format($p['price'], 2); ?></td>
  <td>
    <a href="edit_product.php?id=<?php echo intval($p['id']); ?>" class="button">Edit</a>
    <a href="delete_product.php?id=<?php echo intval($p['id']); ?>" class="button" style="background:#a00;" onclick="return confirm('Delete this product?');">Delete</a>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</body>
</html>
