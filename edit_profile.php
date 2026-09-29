<?php
require_once "config.php";requireLogin();
$stmt=$conn->prepare("SELECT name,email,phone,bio FROM users WHERE id=?");$stmt->bind_param("i",$_SESSION["user_id"]);$stmt->execute();$user=$stmt->get_result()->fetch_assoc();$stmt->close();
$errors=[];
if($_SERVER["REQUEST_METHOD"]==="POST"){
  $name=trim($_POST["name"]??"");$phone=trim($_POST["phone"]??"");$bio=trim($_POST["bio"]??"");
  if(strlen($name)<2)$errors[]="Name is required.";
  if(!$errors){$stmt=$conn->prepare("UPDATE users SET name=?,phone=?,bio=? WHERE id=?");$stmt->bind_param("sssi",$name,$phone,$bio,$_SESSION["user_id"]);$stmt->execute();$stmt->close();$_SESSION["user_name"]=$name;redirect("profile.php");}
}
$pageTitle="Edit profile";require "header.php";
?>
<div class="container py-5"><div class="auth-card"><h2>Edit profile</h2><p class="text-secondary">Update your account details.</p><?php foreach($errors as $e1): ?><div class="alert alert-danger"><?= e($e1) ?></div><?php endforeach; ?><form method="post"><label class="form-label">Name</label><input class="form-control mb-3" name="name" value="<?= e($_POST["name"]??$user["name"]) ?>" required><label class="form-label">Phone</label><input class="form-control mb-3" name="phone" value="<?= e($_POST["phone"]??$user["phone"]) ?>"><label class="form-label">Bio</label><textarea class="form-control mb-3" rows="5" name="bio"><?= e($_POST["bio"]??$user["bio"]) ?></textarea><button class="btn btn-primary">Save changes</button> <a href="profile.php" class="btn btn-outline-secondary">Cancel</a></form></div></div>
<?php require "footer.php"; ?>