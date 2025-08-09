<header class="site-header modern-header">
  <div class="logo-area">
    <img src="images/dog-food.jpg" alt="Dog Food" class="header-img">
    <span class="logo-text">Compet Store</span>
  </div>
  <nav class="main-nav">
    <ul>
      <li><a href="products.php">Products</a></li>
      <li><a href="cart.php" class="cart-link">Cart</a></li>
      <li><a href="checkout.php">Checkout</a></li>
      <li><a href="orders.php">My Orders</a></li>
      <li><a href="profile.php">Profile</a></li>
    </ul>
  </nav>
</header>
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