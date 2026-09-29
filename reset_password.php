<?php
require_once "config.php";$token=$_GET["token"]??$_POST["token"]??"";$error="";$ok="";
$stmt=$conn->prepare("SELECT pr.id,pr.user_id FROM password_resets pr WHERE pr.token=? AND pr.used=0 AND pr.expires_at>NOW() LIMIT 1");$stmt->bind_param("s",$token);$stmt->execute();$reset=$stmt->get_result()->fetch_assoc();$stmt->close();
if(!$reset)$error="This reset link is invalid or expired.";
if($_SERVER["REQUEST_METHOD"]==="POST"&&!$error){
 $pass=$_POST["password"]??"";$confirm=$_POST["confirm"]??"";
 if(strlen($pass)<6||$pass!==$confirm)$error="Password must be 6+ characters and match confirmation.";
 else{$hash=password_hash($pass,PASSWORD_DEFAULT);$stmt=$conn->prepare("UPDATE users SET password=? WHERE id=?");$stmt->bind_param("si",$hash,$reset["user_id"]);$stmt->execute();$stmt->close();$stmt=$conn->prepare("UPDATE password_resets SET used=1 WHERE id=?");$stmt->bind_param("i",$reset["id"]);$stmt->execute();$stmt->close();$ok="Password changed successfully. You can login now.";}
}
$pageTitle="Set password";require "header.php";
?>
<div class="container py-5"><div class="auth-card"><h2>Set new password</h2><?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php elseif($ok): ?><div class="alert alert-success"><?= e($ok) ?><br><a href="login.php">Go to login</a></div><?php else: ?><form method="post"><input type="hidden" name="token" value="<?= e($token) ?>"><input class="form-control mb-3" type="password" name="password" placeholder="New password" required><input class="form-control mb-3" type="password" name="confirm" placeholder="Confirm password" required><button class="btn btn-primary w-100">Change password</button></form><?php endif; ?></div></div>
<?php require "footer.php"; ?>