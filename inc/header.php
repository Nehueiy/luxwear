<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Lux Wear</title>
  <link rel="stylesheet" href="/luxwear/assets/styles.css">
</head>
<body>
  <header class="site-header">
    <div class="container">
      <a href="/luxwear" class="logo">Lux-Wear</a>
      <nav class="nav">
        <a href="/luxwear">Home</a>
        <a href="/luxwear/cart.php">Cart (<?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'],'qty')) : 0; ?>)</a>
        <?php if(isset($_SESSION['user'])): ?>
          <span>Hello, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
          <a href="/luxwear/logout.php">Logout</a>
          <?php if($_SESSION['user']['is_admin']): ?>
            <a href="/luxwear/admin/dashboard.php">Admin</a>
          <?php endif; ?>
        <?php else: ?>
          <a href="/luxwear/login.php">Login</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>
  <main class="container">
