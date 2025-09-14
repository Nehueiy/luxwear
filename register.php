<?php
require 'inc/db.php';
session_start();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email";
  if (strlen($password) < 4) $errors[] = "Password too short";

  if (empty($errors)) {
    // simple hashing for demo (later use password_hash)
    $hash = hash('sha256', $password);
    $stmt = $mysqli->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $name, $email, $hash);
    if ($stmt->execute()) {
      $_SESSION['user'] = ['id' => $stmt->insert_id, 'name' => $name, 'email' => $email, 'is_admin' => 0];
      header('Location: index.php'); exit;
    } else {
      $errors[] = "Email already in use";
    }
  }
}
require 'inc/header.php';
?>
<h1>Register</h1>
<?php foreach($errors as $e) echo "<p style='color:red;'>".htmlspecialchars($e)."</p>"; ?>
<form method="post" action="register.php">
  <div class="form-field"><input type="text" name="name" placeholder="Name" required></div>
  <div class="form-field"><input type="email" name="email" placeholder="Email" required></div>
  <div class="form-field"><input type="password" name="password" placeholder="Password" required></div>
  <button class="btn" type="submit">Register</button>
</form>
<?php require 'inc/footer.php'; ?>
