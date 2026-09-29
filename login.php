<?php
require_once "config.php";
if (isLoggedIn()) redirect("dashboard.php");
$pageTitle = "Login";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = strtolower(trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";
    $stmt = $conn->prepare("SELECT u.id,u.name,u.email,u.password,r.role_name FROM users u JOIN roles r ON u.role_id=r.id WHERE u.email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user["password"])) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["user_email"] = $user["email"];
        $_SESSION["role"] = $user["role_name"];
        redirect("dashboard.php");
    }
    $error = "Invalid email or password.";
}
require "header.php";
?>
<div class="auth-wrap container py-5">
  <div class="auth-card">
    <div class="auth-icon"><i class="bi bi-box-arrow-in-right"></i></div>
    <h2>Welcome back</h2><p class="text-secondary">Login to continue to your dashboard.</p>
    <?php if (isset($_GET["registered"])): ?><div class="alert alert-success">Registration successful. You can login now.</div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
      <label class="form-label">Email</label><input class="form-control mb-3" type="email" name="email" required>
      <label class="form-label">Password</label><div class="input-group mb-2"><input class="form-control" id="loginPass" type="password" name="password" required><button class="btn btn-outline-secondary toggle-pass" type="button" data-target="loginPass"><i class="bi bi-eye"></i></button></div>
      <div class="text-end mb-3"><a href="forgot_password.php">Forgot password?</a></div>
      <button class="btn btn-primary w-100 btn-lg">Login</button>
    </form>
    <div class="demo-box mt-4"><strong>Demo accounts</strong><br>Admin: admin@booknest.com / admin123<br>User: user@booknest.com / user123</div>
  </div>
</div>
<?php require "footer.php"; ?>