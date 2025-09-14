<?php
require 'inc/db.php';
session_start();
$errors=[];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $hash = hash('sha256', $password);
  $stmt = $mysqli->prepare("SELECT id, name, email, is_admin FROM users WHERE email = ? AND password = ?");
  $stmt->bind_param('ss', $email, $hash);
  $stmt->execute();
  $res = $stmt->get_result();
  $user = $res->fetch_assoc();
  if ($user) {
    $_SESSION['user'] = $user;
    // handle return_to
    $return = $_SESSION['return_to'] ?? 'index.php';
    unset($_SESSION['return_to']);
    header('Location: ' . $return);
    exit;
  } else {
    $errors[] = "Invalid credentials";
  }
}
require 'inc/header.php';
?>
<h1>Login</h1>
<?php foreach($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>
<form method="post" action="login.php">
  <div class="form-field"><input type="email" name="email" placeholder="Email" required></div>
  <div class="form-field"><input type="password" name="password" placeholder="Password" required></div>
  <button class="btn" type="submit">Login</button>
</form>
<p>Or <a href="register.php">Register</a></p>
<?php require 'inc/footer.php'; ?>
