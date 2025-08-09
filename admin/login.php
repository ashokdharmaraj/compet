<?php
session_start();

$errors = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    if ($user === 'admin' && $pass === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $errors = "Invalid username or password";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Login</title>
<style>
body{font-family:Arial; background:#f7f7f7; display:flex; justify-content:center; align-items:center; height:100vh;}
form {background:#fff;padding:2rem; border-radius:8px; box-shadow:0 4px 8px rgba(0,0,0,0.1);}
input {display:block; margin:0.5rem 0; padding:0.5rem; width:100%;}
button {padding:0.5rem; width:100%; background:#0b8a6f; border:none; color:#fff; cursor:pointer;}
.error {color:red; margin-bottom:1rem;}
</style>
</head>
<body>
<form method="post" action="">
  <h2>Admin Login</h2>
  <?php if($errors): ?>
  <div class="error"><?php echo htmlspecialchars($errors); ?></div>
  <?php endif; ?>
  <input type="text" name="username" placeholder="Username" required autofocus>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit">Login</button>
</form>
</body>
</html>
