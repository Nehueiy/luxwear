<?php
require '../inc/db.php';
session_start();
$errors=[];
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $hash = hash('sha256', $password);
  $stmt = $mysqli->prepare("SELECT id, name, email, is_admin FROM users WHERE email=? AND password=? AND is_admin=1");
  $stmt->bind_param('ss',$email,$hash);
  $stmt->execute();
  $res = $stmt->get_result();
  $user = $res->fetch_assoc();
  if ($user) {
    $_SESSION['user'] = $user;
    header('Location: dashboard.php'); exit;
  } else {
    $errors[] = "Invalid admin credentials";
  }
}
?>
<!doctype html><html><head><link rel="stylesheet" href="/luxwear/assets/styles.css"></head><body>
<div class="container">
<h1>Admin Login</h1>
<?php foreach($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>
<form method="post" action="login.php">
  <div class="form-field"><input type="email" name="email" placeholder="Email" required></div>
  <div class="form-field"><input type="password" name="password" placeholder="Password" required></div>
  <button class="btn" type="submit">Login</button>
</form>
</div></body></html>
