<?php
require_once "config.php";
$pageTitle="Forgot password";$message="";$error="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
 $email=strtolower(trim($_POST["email"]??""));$stmt=$conn->prepare("SELECT id FROM users WHERE email=?");$stmt->bind_param("s",$email);$stmt->execute();$u=$stmt->get_result()->fetch_assoc();$stmt->close();
 if($u){$token=bin2hex(random_bytes(16));$expires=date("Y-m-d H:i:s",time()+1800);$stmt=$conn->prepare("INSERT INTO password_resets(user_id,token,expires_at) VALUES(?,?,?)");$stmt->bind_param("iss",$u["id"],$token,$expires);$stmt->execute();$stmt->close();$message="Demo reset link: reset_password.php?token=".$token;}
 else $message="If that email exists, a reset link has been created (demo mode).";
}
require "header.php";
?>
<div class="container py-5"><div class="auth-card"><div class="auth-icon"><i class="bi bi-key"></i></div><h2>Reset password</h2><p class="text-secondary">Enter your account email.</p><?php if($message): ?><div class="alert alert-success"><?= e($message) ?></div><?php endif; ?><form method="post"><input class="form-control mb-3" type="email" name="email" placeholder="you@example.com" required><button class="btn btn-primary w-100">Generate reset link</button></form></div></div>
<?php require "footer.php"; ?>