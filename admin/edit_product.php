<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
require '../db.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: index.php');
    exit;
}

// Fetch product data
$stmt = $mysqli->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$product = $res->fetch_assoc();
$stmt->close();

if (!$product) {
    header('Location: index.php');
    exit;
}

$errors = [];
$name = $product['name'];
$description = $product['description'];
$price = $product['price'];
$current_image = $product['image'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);

    if (!$name) $errors[] = "Product name is required";
    if ($price <= 0) $errors[] = "Price must be greater than zero";

    // Image upload handling (optional)
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
            } else {
                // Delete old image
                if ($current_image && file_exists(__DIR__ . "/uploads/" . $current_image)) {
                    unlink(__DIR__ . "/uploads/" . $current_image);
                }
                $current_image = $image_name;
            }
        }
    }

    if (!$errors) {
        $stmt = $mysqli->prepare("UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
        $stmt->bind_param('ssdsi', $name, $description, $price, $current_image, $id);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $errors[] = "Database update failed: " . $mysqli->error;
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Edit Product</title>
<style>
body{font-family:Arial; max-width:600px; margin:2rem auto;}
form {background:#fff; padding:1rem; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1);}
label {display:block; margin:0.75rem 0 0.25rem;}
input[type=text], input[type=number], textarea {width:100%; padding:0.5rem; border:1px solid #ccc; border-radius:4px;}
button {margin-top:1rem; padding:0.5rem 1rem; background:#0b8a6f; border:none; color:#fff; cursor:pointer;}
.errors {background:#fdd; border:1px solid #a00; padding:0.5rem; margin-bottom:1rem;}
img {max-width:120px; margin-top:0.5rem; border-radius:6px;}
a {text-decoration:none; color:#0b8a6f;}
</style>
</head>
<body>
<h1>Edit Product</h1>
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

  <label>Current Image</label>
  <img src="../images/<?php echo htmlspecialchars($current_image); ?>" alt="">

  <label>Change Image (optional)</label>
  <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif">

  <button type="submit">Update Product</button>
</form>
<p><a href="index.php">Back to product list</a></p>
</body>
</html>
