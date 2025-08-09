<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register - Compet Store</title>
  <style>
    body { background: linear-gradient(135deg, #f5f7fa 0%, #c3dafe 100%); min-height: 100vh; margin: 0; font-family: 'Segoe UI', 'Arial', sans-serif; }
    .container { max-width: 400px; margin: 3rem auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(44,62,80,0.07); padding: 2rem; }
    .form-title { text-align: center; font-size: 1.5rem; margin-bottom: 1.5rem; color: #1a237e; }
    .form-group { margin-bottom: 1.2rem; }
    label { display: block; margin-bottom: 0.4rem; color: #1a237e; }
    input[type="text"], input[type="password"], input[type="email"] { width: 100%; padding: 0.6rem; border-radius: 5px; border: 1px solid #b3b3b3; }
    button { width: 100%; padding: 0.7rem; background: #1a237e; color: #fff; border: none; border-radius: 5px; font-size: 1.1rem; font-weight: 500; cursor: pointer; }
    button:hover { background: #3949ab; }
    .login-link { text-align: center; margin-top: 1rem; }
    .login-link a { color: #1a237e; text-decoration: underline; }
  </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
  <div class="form-title">Register</div>
  <form method="post" action="register_action.php">
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required autocomplete="username">
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required autocomplete="email">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required autocomplete="new-password">
    </div>
    <button type="submit">Register</button>
  </form>
  <div class="login-link">
    Already have an account? <a href="login.php">Login</a>
  </div>
</div>
</body>
</html>