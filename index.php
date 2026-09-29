<?php
$pageTitle = "Home";
require "header.php";
?>
<section class="hero">
  <div class="container py-5">
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <span class="eyebrow"><i class="bi bi-stars"></i> Real-World Full Stack Project</span>
        <h1 class="display-4 fw-bold mt-3">Find your next <span>great read.</span></h1>
        <p class="lead text-secondary mt-3">A responsive online bookstore with authentication, product management, shopping cart, orders, search, filters and an admin panel.</p>
        <div class="d-flex flex-wrap gap-3 mt-4">
          <a class="btn btn-primary btn-lg" href="products.php">Browse Books <i class="bi bi-arrow-right ms-1"></i></a>
          <?php if (!isLoggedIn()): ?><a class="btn btn-outline-dark btn-lg" href="register.php">Create Account</a><?php endif; ?>
        </div>
        <div class="hero-stats mt-4">
          <div><strong>CRUD</strong><small>Products & users</small></div>
          <div><strong>Secure</strong><small>Sessions + hashes</small></div>
          <div><strong>Responsive</strong><small>Mobile-first UI</small></div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="book-stack">
          <div class="floating-book book-a">Clean<br>Code</div>
          <div class="floating-book book-b">The<br>Alchemist</div>
          <div class="floating-book book-c">Atomic<br>Habits</div>
          <div class="book-glow"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container py-5">
  <div class="section-heading">
    <span>Project Modules</span><h2>Everything in one application</h2>
  </div>
  <div class="row g-4">
    <?php
    $features = [
      ["bi-person-check","Authentication","Register, login, logout, sessions and role-based access."],
      ["bi-bag-check","Shopping Flow","Search books, add to cart, update quantities and place orders."],
      ["bi-speedometer2","Admin Panel","Manage users, products and orders with CRUD operations."]
    ];
    foreach ($features as $f):
    ?>
    <div class="col-md-4"><div class="feature-card h-100"><i class="bi <?= $f[0] ?>"></i><h4><?= e($f[1]) ?></h4><p><?= e($f[2]) ?></p></div></div>
    <?php endforeach; ?>
  </div>
</section>
<?php require "footer.php"; ?>