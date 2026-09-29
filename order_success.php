<?php
require_once "config.php"; requireLogin();
$id=(int)($_GET["id"]??0);
$pageTitle="Order placed";require "header.php";
?>
<div class="container py-5"><div class="success-card"><div class="success-icon"><i class="bi bi-check2"></i></div><h1>Order placed!</h1><p class="lead text-secondary">Thanks, <?= e($_SESSION["user_name"]) ?>. Your order <strong>#<?= $id ?></strong> has been created successfully.</p><div class="d-flex justify-content-center gap-2 mt-4"><a href="orders.php" class="btn btn-primary">View orders</a><a href="products.php" class="btn btn-outline-secondary">Continue shopping</a></div></div></div>
<?php require "footer.php"; ?>