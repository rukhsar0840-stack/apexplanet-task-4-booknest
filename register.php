<?php
require_once "config.php";
if (isLoggedIn()) redirect("dashboard.php");
$pageTitle = "Register";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = strtolower(trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";
    $confirm = $_POST["confirm_password"] ?? "";

    if (strlen($name) < 2) $errors[] = "Name must contain at least 2 characters.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid email.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm) $errors[] = "Passwords do not match.";

    if (!$errors) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows) $errors[] = "Email is already registered.";
        $stmt->close();
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $role = "User";
        $stmt = $conn->prepare("INSERT INTO users (role_id,name,email,password) SELECT id,?,?,? FROM roles WHERE role_name=?");
        $stmt->bind_param("ssss", $name, $email, $hash, $role);
        if ($stmt->execute()) redirect("login.php?registered=1");
        $errors[] = "Registration failed. Please try again.";
        $stmt->close();
    }
}
require "header.php";
?>
<div class="auth-wrap container py-5">
  <div class="auth-card">
    <div class="auth-icon"><i class="bi bi-person-plus"></i></div>
    <h2>Create account</h2><p class="text-secondary">Join BookNest and start building your reading list.</p>
    <?php foreach($errors as $err): ?><div class="alert alert-danger"><?= e($err) ?></div><?php endforeach; ?>
    <form method="post" novalidate>
      <label class="form-label">Full name</label><input class="form-control mb-3" name="name" required value="<?= e($_POST["name"] ?? "") ?>">
      <label class="form-label">Email</label><input class="form-control mb-3" type="email" name="email" required value="<?= e($_POST["email"] ?? "") ?>">
      <label class="form-label">Password</label><div class="input-group mb-3"><input class="form-control" id="regPass" type="password" name="password" required><button class="btn btn-outline-secondary toggle-pass" type="button" data-target="regPass"><i class="bi bi-eye"></i></button></div>
      <label class="form-label">Confirm password</label><input class="form-control mb-3" type="password" name="confirm_password" required>
      <div class="form-check mb-3"><input class="form-check-input" type="checkbox" required id="terms"><label class="form-check-label" for="terms">I agree to the demo terms.</label></div>
      <button class="btn btn-primary w-100 btn-lg">Create Account</button>
    </form>
    <p class="text-center mt-3 mb-0">Already registered? <a href="login.php">Login</a></p>
  </div>
</div>
<?php require "footer.php"; ?>