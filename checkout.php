<?php
require_once "config.php"; requireLogin();
if(empty($_SESSION["cart"])) redirect("products.php");
$ids=array_map("intval",array_keys($_SESSION["cart"]));$in=implode(",",array_fill(0,count($ids),"?"));$types=str_repeat("i",count($ids));
$stmt=$conn->prepare("SELECT * FROM products WHERE id IN ($in) AND is_active=1");$stmt->bind_param($types,...$ids);$stmt->execute();$rows=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);$stmt->close();
$total=0;foreach($rows as $p)$total+=(int)$_SESSION["cart"][$p["id"]]*$p["price"];
$error="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $address=trim($_POST["address"]??"");$phone=trim($_POST["phone"]??"");
    if(strlen($address)<10)$error="Please enter a complete delivery address.";
    if(!$error){
        $conn->begin_transaction();
        try{
            $stmt=$conn->prepare("INSERT INTO orders(user_id,total_amount,status,address,phone) VALUES(?,?,?,?,?)");$status="Pending";$stmt->bind_param("idsss",$_SESSION["user_id"],$total,$status,$address,$phone);$stmt->execute();$orderId=$conn->insert_id;$stmt->close();
            $lineStmt=$conn->prepare("INSERT INTO order_items(order_id,product_id,quantity,price) VALUES(?,?,?,?)");
            $stockStmt=$conn->prepare("UPDATE products SET stock=GREATEST(stock-?,0) WHERE id=? AND stock>=?");
            foreach($rows as $p){$qty=(int)$_SESSION["cart"][$p["id"]];$price=$p["price"];$pid=$p["id"];$lineStmt->bind_param("iiid",$orderId,$pid,$qty,$price);$lineStmt->execute();$stockStmt->bind_param("iii",$qty,$pid,$qty);$stockStmt->execute();if($stockStmt->affected_rows<1)throw new Exception("Stock changed.");}
            $lineStmt->close();$stockStmt->close();$conn->commit();$_SESSION["cart"]=[];redirect("order_success.php?id=".$orderId);
        }catch(Exception $ex){$conn->rollback();$error="Could not place order. Please check stock and try again.";}
    }
}
$pageTitle="Checkout";require "header.php";
?>
<div class="container py-5"><div class="row justify-content-center"><div class="col-lg-9"><div class="section-heading"><span>Secure checkout</span><h1>Complete your order</h1></div>
<?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="card-soft"><div class="row g-3"><div class="col-md-6"><label class="form-label">Phone</label><input class="form-control" name="phone" required></div><div class="col-12"><label class="form-label">Delivery address</label><textarea class="form-control" name="address" rows="4" required></textarea></div><div class="col-12"><div class="summary-card"><div class="d-flex justify-content-between"><span>Total</span><strong>₹<?= number_format($total,2) ?></strong></div></div></div><div class="col-12"><button class="btn btn-primary btn-lg w-100">Place order</button></div></div></form>
</div></div></div>
<?php require "footer.php"; ?>