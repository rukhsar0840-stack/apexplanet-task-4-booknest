<?php
require_once "config.php";
requireLogin();
$pageTitle = "Dashboard";
$orders = [];
$stmt = $conn->prepare("SELECT o.id,o.total_amount,o.status,o.created_at,COUNT(oi.id) item_count FROM orders o LEFT JOIN order_items oi ON o.id=oi.order_id WHERE o.user_id=? GROUP BY o.id ORDER BY o.id DESC LIMIT 8");
$stmt->bind_param("i", $_SESSION["user_id"]); $stmt->execute(); $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close();
require "header.php";
?>
<div class="container py-5">
  <div class="dashboard-hero"><div><span class="eyebrow">Dashboard</span><h1 class="mt-2">Hello, <?= e($_SESSION["user_name"]) ?> 👋</h1><p class="mb-0">Manage your account and keep track of your orders.</p></div><a href="products.php" class="btn btn-light">Shop Books</a></div>
  <div class="row g-4 mt-1">
    <div class="col-md-4"><div class="stat-card"><i class="bi bi-person"></i><span>Account</span><strong><?= e($_SESSION["role"]) ?></strong></div></div>
    <div class="col-md-4"><div class="stat-card"><i class="bi bi-receipt"></i><span>Your orders</span><strong><?= count($orders) ?></strong></div></div>
    <div class="col-md-4"><div class="stat-card"><i class="bi bi-shield-check"></i><span>Security</span><strong>Protected</strong></div></div>
  </div>
  <div class="card-soft mt-4"><div class="d-flex justify-content-between align-items-center mb-3"><h3 class="h5 mb-0">Recent orders</h3><a href="orders.php">View all</a></div>
  <?php if (!$orders): ?><p class="text-secondary mb-0">No orders yet. Browse the books to place your first order.</p>
  <?php else: ?><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Order</th><th>Items</th><th>Total</th><th>Status</th><th>Date</th></tr></thead><tbody>
  <?php foreach($orders as $o): ?><tr><td>#<?= $o["id"] ?></td><td><?= $o["item_count"] ?></td><td>₹<?= number_format($o["total_amount"],2) ?></td><td><span class="badge text-bg-light"><?= e($o["status"]) ?></span></td><td><?= e(date("d M Y", strtotime($o["created_at"]))) ?></td></tr><?php endforeach; ?>
  </tbody></table></div><?php endif; ?></div>
</div>
<?php require "footer.php"; ?>