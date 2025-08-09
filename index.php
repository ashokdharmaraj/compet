<?php
// Simulated database results for now
$featuredProducts = [
    ["id" => 1, "name" => "Premium Dog Food", "price" => 19.99, "image" => "images/dog-food.jpg"],
    ["id" => 2, "name" => "Cat Scratching Post", "price" => 25.00, "image" => "images/cat-post.jpg"],
    ["id" => 3, "name" => "Bird Cage", "price" => 89.50, "image" => "images/bird-cage.jpg"],
    ["id" => 4, "name" => "Fish Tank (30L)", "price" => 45.00, "image" => "images/fish-tank.jpg"],
];

$newArrivals = [
    ["id" => 5, "name" => "Dog Chew Toy", "price" => 5.50, "image" => "images/dog-toy.jpg"],
    ["id" => 6, "name" => "Cat Bed", "price" => 35.00, "image" => "images/cat-bed.jpg"],
    ["id" => 7, "name" => "Bird Feeder", "price" => 12.00, "image" => "images/bird-feeder.jpg"],
    ["id" => 8, "name" => "Aquarium Filter", "price" => 20.00, "image" => "images/aquarium-filter.jpg"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Shop - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-card img {
            height: 200px;
            object-fit: cover;
        }
        .hero {
            background: url('images/pet-banner.jpg') center/cover no-repeat;
            height: 400px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.7);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">🐾 Pet Shop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" 
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Shop</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Cart</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Login</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Banner -->
<div class="hero text-center">
    <h1 class="display-3 fw-bold">Welcome to Pet Shop</h1>
    <p class="lead">Everything your furry, feathery, and finned friends need 🐶🐱🐦🐠</p>
    <a href="#" class="btn btn-lg btn-primary mt-3">Shop Now</a>
</div>

<!-- Featured Products -->
<div class="container py-5">
    <h2 class="mb-4 text-center">Featured Products</h2>
    <div class="row g-4">
        <?php foreach ($featuredProducts as $product): ?>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card product-card h-100">
                    <img src="<?= htmlspecialchars($product['image']) ?>" 
                         class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text text-success fw-bold">$<?= number_format($product['price'], 2) ?></p>
                        <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-outline-primary mt-auto">View Details</a>
                        <a href="cart.php?action=add&id=<?= $product['id'] ?>" class="btn btn-primary mt-2">Add to Cart</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- New Arrivals -->
<div class="container py-5">
    <h2 class="mb-4 text-center">New Arrivals</h2>
    <div class="row g-4">
        <?php foreach ($newArrivals as $product): ?>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card product-card h-100">
                    <img src="<?= htmlspecialchars($product['image']) ?>" 
                         class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text text-success fw-bold">$<?= number_format($product['price'], 2) ?></p>
                        <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-outline-primary mt-auto">View Details</a>
                        <a href="cart.php?action=add&id=<?= $product['id'] ?>" class="btn btn-primary mt-2">Add to Cart</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-light text-center p-3 mt-5">
    &copy; <?= date('Y') ?> Pet Shop. All Rights Reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
