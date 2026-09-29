<?php
require_once "config.php";requireAdmin();
$pageTitle="Admin Panel";
$stats=[];
$stats["users"]=(int)$conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()["c"];
$stats["products"]=(int)$conn->query("SELECT COUNT(*) c FROM products")->fetch_assoc()["c"];
$stats["orders"]=(int)$conn->query("SELECT COUNT(*) c FROM orders")->fetch_assoc()["c"];
$stats["revenue"]=(float)$conn->query("SELECT COALESCE(SUM(total_amount),0) s FROM orders WHERE status<>'Cancelled'")->fetch_assoc()["s"];
$users=$conn->query("SELECT u.id,u.name,u.email,r.role_name,u.created_at FROM users u JOIN roles r ON u.role_id=r.id ORDER BY u.id DESC LIMIT 10")->fetch_all(MYSQLI_ASSOC);
$products=$conn->query("SELECT id,title,author,price,stock,is_active FROM products ORDER BY id DESC LIMIT 10")->fetch_all(MYSQLI_ASSOC);
$orders=$conn->query("SELECT o.id,o.total_amount,o.status,o.created_at,u.name FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.id DESC LIMIT 10")->fetch_all(MYSQLI_ASSOC);
require "header.php";
?>
<div class="container py-5"><div class="section-heading"><span>Admin workspace</span><h1>Control center</h1></div>
<div class="row g-3"><?php foreach([["users","Users",$stats["users"],"bi-people"],["products","Products",$stats["products"],"bi-book"],["orders","Orders",$stats["orders"],"bi-receipt"],["revenue","Revenue","₹".number_format($stats["revenue"],0),"bi-graph-up-arrow"]] as $s): ?><div class="col-6 col-lg-3"><div class="stat-card"><i class="bi <?= $s[3] ?>"></i><span><?= $s[1] ?></span><strong><?= $s[2] ?></strong></div></div><?php endforeach; ?></div>
<div class="d-flex gap-2 flex-wrap mt-4"><a class="btn btn-primary" href="admin_product.php">+ Add product</a><a class="btn btn-outline-secondary" href="admin_orders.php">Manage orders</a></div>
<div class="card-soft mt-4"><div class="d-flex justify-content-between"><h3 class="h5">Users</h3></div><div class="table-responsive"><table class="table"><thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th></th></tr></thead><tbody><?php foreach($users as $u): ?><tr><td><?= e($u["name"]) ?></td><td><?= e($u["email"]) ?></td><td><?= e($u["role_name"]) ?></td><td><?= e(date("d M Y",strtotime($u["created_at"]))) ?></td><td><?php if($u["id"]!=$_SESSION["user_id"]): ?><a class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this user?')" href="admin_user_delete.php?id=<?= $u["id"] ?>">Delete</a><?php endif; ?></td></tr><?php endforeach; ?></tbody></table></div></div>
<div class="card-soft mt-4"><h3 class="h5">Products</h3><div class="table-responsive"><table class="table"><thead><tr><th>Title</th><th>Author</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead><tbody><?php foreach($products as $p): ?><tr><td><?= e($p["title"]) ?></td><td><?= e($p["author"]) ?></td><td>₹<?= number_format($p["price"],2) ?></td><td><?= $p["stock"] ?></td><td><a class="btn btn-sm btn-outline-primary" href="admin_product.php?id=<?= $p["id"] ?>">Edit</a> <a class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this product?')" href="admin_product_delete.php?id=<?= $p["id"] ?>">Delete</a></td></tr><?php endforeach; ?></tbody></table></div></div>
<div class="card-soft mt-4"><h3 class="h5">Recent orders</h3><div class="table-responsive"><table class="table"><thead><tr><th>#</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead><tbody><?php foreach($orders as $o): ?><tr><td>#<?= $o["id"] ?></td><td><?= e($o["name"]) ?></td><td>₹<?= number_format($o["total_amount"],2) ?></td><td><?= e($o["status"]) ?></td><td><?= e(date("d M",strtotime($o["created_at"]))) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
</div>
<?php require "footer.php"; ?>