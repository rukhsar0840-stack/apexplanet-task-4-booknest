<?php
require_once "config.php";requireLogin();
$pageTitle="Orders";
$stmt=$conn->prepare("SELECT id,total_amount,status,address,created_at FROM orders WHERE user_id=? ORDER BY id DESC");$stmt->bind_param("i",$_SESSION["user_id"]);$stmt->execute();$orders=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);$stmt->close();
require "header.php";
?>
<div class="container py-5"><div class="section-heading"><span>Purchase history</span><h1>Your orders</h1></div><div class="card-soft">
<?php if(!$orders): ?><p class="mb-0 text-secondary">No orders yet.</p><?php else: ?><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Order</th><th>Total</th><th>Status</th><th>Date</th></tr></thead><tbody><?php foreach($orders as $o): ?><tr><td><a href="order_detail.php?id=<?= $o["id"] ?>">#<?= $o["id"] ?></a></td><td>₹<?= number_format($o["total_amount"],2) ?></td><td><span class="badge text-bg-light"><?= e($o["status"]) ?></span></td><td><?= e(date("d M Y, h:i A",strtotime($o["created_at"]))) ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
</div></div>
<?php require "footer.php"; ?>