<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login - Compet Store</title>
  <style>
    body { background: linear-gradient(135deg, #f5f7fa 0%, #c3dafe 100%); min-height: 100vh; margin: 0; font-family: 'Segoe UI', 'Arial', sans-serif; }
    .container { max-width: 400px; margin: 3rem auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(44,62,80,0.07); padding: 2rem; }
    .form-title { text-align: center; font-size: 1.5rem; margin-bottom: 1.5rem; color: #1a237e; }
    .form-group { margin-bottom: 1.2rem; }
    label { display: block; margin-bottom: 0.4rem; color: #1a237e; }
    input[type="text"], input[type="password"] { width: 100%; padding: 0.6rem; border-radius: 5px; border: 1px solid #b3b3b3; }
    button { width: 100%; padding: 0.7rem; background: #1a237e; color: #fff; border: none; border-radius: 5px; font-size: 1.1rem; font-weight: 500; cursor: pointer; }
    button:hover { background: #3949ab; }
    .register-link { text-align: center; margin-top: 1rem; }
    .register-link a { color: #1a237e; text-decoration: underline; }
  </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
  <div class="form-title">Login</div>
  <form method="post" action="login_action.php">
    <div class="form-group">
      <label for="username">Username or Email</label>
      <input type="text" id="username" name="username" required autocomplete="username">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required autocomplete="current-password">
    </div>
    <button type="submit">Login</button>
  </form>
  <div class="register-link">
    Don't have an account? <a href="register.php">Register</a>
  </div>
</div>
</body>
</html>