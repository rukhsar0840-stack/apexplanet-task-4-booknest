<?php
require_once "config.php";requireAdmin();
$id=(int)($_GET["id"]??0);$errors=[];
$product=["title"=>"","author"=>"","category"=>"Fiction","price"=>"","stock"=>"10","description"=>""];
if($id){$stmt=$conn->prepare("SELECT * FROM products WHERE id=?");$stmt->bind_param("i",$id);$stmt->execute();$product=$stmt->get_result()->fetch_assoc();$stmt->close();if(!$product)redirect("admin.php");}
if($_SERVER["REQUEST_METHOD"]==="POST"){
 $title=trim($_POST["title"]??"");$author=trim($_POST["author"]??"");$category=trim($_POST["category"]??"");$price=(float)($_POST["price"]??0);$stock=(int)($_POST["stock"]??0);$description=trim($_POST["description"]??"");
 if(strlen($title)<2)$errors[]="Title is required.";if($price<=0)$errors[]="Price must be greater than 0.";if($stock<0)$errors[]="Stock cannot be negative.";
 if(!$errors){
  if($id){$stmt=$conn->prepare("UPDATE products SET title=?,author=?,category=?,price=?,stock=?,description=? WHERE id=?");$stmt->bind_param("sssdisi",$title,$author,$category,$price,$stock,$description,$id);}
  else{$stmt=$conn->prepare("INSERT INTO products(title,author,category,price,stock,description) VALUES(?,?,?,?,?,?)");$stmt->bind_param("sssdis",$title,$author,$category,$price,$stock,$description);}
  if($stmt->execute())redirect("admin.php");$errors[]="Could not save product."; $stmt->close();
 }
}
$pageTitle=$id?"Edit product":"Add product";require "header.php";
?>
<div class="container py-5"><div class="auth-card"><h2><?= $id?"Edit product":"Add product" ?></h2><?php foreach($errors as $er): ?><div class="alert alert-danger"><?= e($er) ?></div><?php endforeach; ?><form method="post"><div class="row g-3"><div class="col-md-6"><label class="form-label">Title</label><input class="form-control" name="title" value="<?= e($_POST["title"]??$product["title"]) ?>" required></div><div class="col-md-6"><label class="form-label">Author</label><input class="form-control" name="author" value="<?= e($_POST["author"]??$product["author"]) ?>" required></div><div class="col-md-6"><label class="form-label">Category</label><input class="form-control" name="category" value="<?= e($_POST["category"]??$product["category"]) ?>"></div><div class="col-md-3"><label class="form-label">Price</label><input class="form-control" type="number" step="0.01" name="price" value="<?= e($_POST["price"]??$product["price"]) ?>" required></div><div class="col-md-3"><label class="form-label">Stock</label><input class="form-control" type="number" name="stock" value="<?= e($_POST["stock"]??$product["stock"]) ?>" required></div><div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="5"><?= e($_POST["description"]??$product["description"]) ?></textarea></div></div><button class="btn btn-primary mt-4">Save product</button> <a href="admin.php" class="btn btn-outline-secondary mt-4">Cancel</a></form></div></div>
<?php require "footer.php"; ?>