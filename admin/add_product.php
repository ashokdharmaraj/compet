<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
require '../db.php';

$errors = [];
$name = $description = $price = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);

    if (!$name) $errors[] = "Product name is required";
    if ($price <= 0) $errors[] = "Price must be greater than zero";

    // Image upload handling
    $image_name = null;
    if (!empty($_FILES['image']['name'])) {
        $target_dir = __DIR__ . "/uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);

        $tmp_name = $_FILES['image']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        if (!in_array($ext, $allowed)) {
            $errors[] = "Invalid image format (jpg, png, gif allowed)";
        } else {
            $image_name = uniqid('img_') . '.' . $ext;
            $target_file = $target_dir . $image_name;
            if (!move_uploaded_file($tmp_name, $target_file)) {
                $errors[] = "Failed to upload image";
            }
        }
    } else {
        $errors[] = "Product image is required";
    }

    if (!$errors) {
        $stmt = $mysqli->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssds', $name, $description, $price, $image_name);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $errors[] = "Database insert failed: " . $mysqli->error;
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Add Product</title>
<style>
body{font-family:Arial; max-width:600px; margin:2rem auto;}
form {background:#fff; padding:1rem; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1);}
label {display:block; margin:0.75rem 0 0.25rem;}
input[type=text], input[type=number], textarea {width:100%; padding:0.5rem; border:1px solid #ccc; border-radius:4px;}
button {margin-top:1rem; padding:0.5rem 1rem; background:#0b8a6f; border:none; color:#fff; cursor:pointer;}
.errors {background:#fdd; border:1px solid #a00; padding:0.5rem; margin-bottom:1rem;}
a {text-decoration:none; color:#0b8a6f;}
</style>
</head>
<body>
<h1>Add New Product</h1>
<?php if ($errors): ?>
<div class="errors">
  <ul>
    <?php foreach ($errors as $e): ?>
    <li><?php echo htmlspecialchars($e); ?></li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>
<form method="post" enctype="multipart/form-data">
  <label>Product Name</label>
  <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

  <label>Description</label>
  <textarea name="description" rows="4"><?php echo htmlspecialchars($description); ?></textarea>

  <label>Price (₹)</label>
  <input type="number" name="price" min="0" step="0.01" value="<?php echo htmlspecialchars($price); ?>" required>

  <label>Image (jpg, png, gif)</label>
  <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif" required>

  <button type="submit">Add Product</button>
</form>
<p><a href="index.php">Back to product list</a></p>
</body>
</html>
