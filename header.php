<?php
require_once __DIR__ . "/config.php";
$current = basename($_SERVER["PHP_SELF"]);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle ?? "BookNest") ?> | ApexPlanet Task 4</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-book-half me-2"></i>BookNest</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link <?= $current==="index.php"?"active":"" ?>" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link <?= $current==="products.php"?"active":"" ?>" href="products.php">Books</a></li>
        <?php if (isLoggedIn()): ?>
          <li class="nav-item"><a class="nav-link <?= $current==="dashboard.php"?"active":"" ?>" href="dashboard.php">Dashboard</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <a class="btn btn-sm btn-outline-light position-relative" href="cart.php">
          <i class="bi bi-cart3"></i> Cart
          <?php
          $cartCount = 0;
          if (!empty($_SESSION["cart"])) foreach ($_SESSION["cart"] as $qty) $cartCount += (int)$qty;
          if ($cartCount) echo '<span class="cart-badge">'.$cartCount.'</span>';
          ?>
        </a>
        <?php if (isLoggedIn()): ?>
          <?php if (isAdmin()): ?><a class="btn btn-sm btn-warning" href="admin.php"><i class="bi bi-speedometer2"></i> Admin</a><?php endif; ?>
          <a class="btn btn-sm btn-light" href="profile.php"><i class="bi bi-person"></i> <?= e($_SESSION["user_name"]) ?></a>
          <a class="btn btn-sm btn-outline-light" href="logout.php">Logout</a>
        <?php else: ?>
          <a class="btn btn-sm btn-outline-light" href="login.php">Login</a>
          <a class="btn btn-sm btn-primary" href="register.php">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<main>