<?php
require_once "config.php";
$id=(int)($_GET["id"] ?? 0);
$stmt=$conn->prepare("SELECT * FROM products WHERE id=? AND is_active=1 LIMIT 1"); $stmt->bind_param("i",$id); $stmt->execute(); $book=$stmt->get_result()->fetch_assoc(); $stmt->close();
if(!$book) redirect("products.php");
$pageTitle=$book["title"];
require "header.php";
?>
<div class="container py-5"><div class="row g-5 align-items-center">
  <div class="col-md-5"><div class="detail-cover"><?= e(strtoupper(substr($book["title"],0,1))) ?></div></div>
  <div class="col-md-7"><span class="eyebrow"><?= e($book["category"]) ?></span><h1 class="mt-3"><?= e($book["title"]) ?></h1><p class="lead text-secondary">by <?= e($book["author"]) ?></p><p><?= e($book["description"]) ?></p><div class="price-big">₹<?= number_format($book["price"],2) ?></div><p class="small text-secondary"><?= (int)$book["stock"] ?> copies available</p>
  <form method="post" action="cart.php" class="d-flex gap-2 align-items-center mt-4"><input type="hidden" name="product_id" value="<?= $book["id"] ?>"><input class="form-control qty-input" type="number" name="quantity" value="1" min="1" max="<?= max(1,(int)$book["stock"]) ?>"><button class="btn btn-primary btn-lg">Add to cart <i class="bi bi-cart-plus ms-1"></i></button></form></div>
</div></div>
<?php require "footer.php"; ?>