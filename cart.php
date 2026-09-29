<?php
require_once "config.php";
if (!isset($_SESSION["cart"])) $_SESSION["cart"]=[];
if ($_SERVER["REQUEST_METHOD"]==="POST") {
    $id=(int)($_POST["product_id"] ?? 0); $qty=max(1,(int)($_POST["quantity"] ?? 1));
    $stmt=$conn->prepare("SELECT stock FROM products WHERE id=? AND is_active=1"); $stmt->bind_param("i",$id); $stmt->execute(); $p=$stmt->get_result()->fetch_assoc(); $stmt->close();
    if($p){ $current=(int)($_SESSION["cart"][$id]??0); $_SESSION["cart"][$id]=min($current+$qty,(int)$p["stock"]); }
    redirect("cart.php");
}
if(isset($_GET["remove"])){ unset($_SESSION["cart"][(int)$_GET["remove"]]); redirect("cart.php"); }
if(isset($_POST["update_cart"])){
    foreach(($_POST["qty"]??[]) as $id=>$qty){ $id=(int)$id;$qty=(int)$qty;if($qty>0)$_SESSION["cart"][$id]=min(99,$qty);else unset($_SESSION["cart"][$id]); }
    redirect("cart.php");
}
$items=[];$subtotal=0;
if($_SESSION["cart"]){
    $ids=array_map("intval",array_keys($_SESSION["cart"])); $in=implode(",",array_fill(0,count($ids),"?")); $types=str_repeat("i",count($ids));
    $stmt=$conn->prepare("SELECT * FROM products WHERE id IN ($in)"); $stmt->bind_param($types,...$ids);$stmt->execute();$rows=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);$stmt->close();
    foreach($rows as $p){$qty=(int)$_SESSION["cart"][$p["id"]];$line=$qty*$p["price"];$subtotal+=$line;$items[]=["p"=>$p,"qty"=>$qty,"line"=>$line];}
}
$pageTitle="Cart";require "header.php";
?>
<div class="container py-5"><div class="section-heading"><span>Shopping cart</span><h1>Your cart</h1></div>
<?php if(!$items): ?><div class="empty-state"><i class="bi bi-cart-x"></i><h4>Your cart is empty</h4><a href="products.php" class="btn btn-primary mt-2">Browse books</a></div>
<?php else: ?>
<form method="post"><input type="hidden" name="update_cart" value="1"><div class="row g-4"><div class="col-lg-8">
<?php foreach($items as $item): $p=$item["p"]; ?><div class="cart-item"><div class="mini-cover"><?= e(strtoupper(substr($p["title"],0,1))) ?></div><div class="flex-grow-1"><h5><?= e($p["title"]) ?></h5><p class="small text-secondary mb-2"><?= e($p["author"]) ?></p><input class="form-control qty-input" type="number" min="1" name="qty[<?= $p["id"] ?>]" value="<?= $item["qty"] ?>"></div><strong>₹<?= number_format($item["line"],2) ?></strong><a href="?remove=<?= $p["id"] ?>" class="text-danger ms-2"><i class="bi bi-trash"></i></a></div><?php endforeach; ?>
<button class="btn btn-outline-secondary">Update cart</button></div>
<div class="col-lg-4"><div class="summary-card"><h4>Order summary</h4><div class="d-flex justify-content-between"><span>Subtotal</span><strong>₹<?= number_format($subtotal,2) ?></strong></div><div class="d-flex justify-content-between mt-2"><span>Delivery</span><span>Free</span></div><hr><div class="d-flex justify-content-between"><strong>Total</strong><strong>₹<?= number_format($subtotal,2) ?></strong></div><a href="checkout.php" class="btn btn-primary w-100 mt-3">Proceed to checkout</a></div></div></div></form>
<?php endif; ?></div>
<?php require "footer.php"; ?>