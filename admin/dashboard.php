<?php
require '../inc/db.php';
session_start();
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
  header('Location: login.php'); exit;
}
require '../inc/header.php';
?>
<h1>Admin Dashboard</h1>
<p><a href="add_product.php" class="btn">Add Product</a></p>
<h2>Products</h2>
<?php
$res = $mysqli->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $res->fetch_all(MYSQLI_ASSOC);
?>
<table style="width:100%; background:#fff;">
<tr><th>Title</th><th>Price</th><th>Inventory</th></tr>
<?php foreach($products as $p): ?>
<tr>
  <td><?php echo htmlspecialchars($p['title']); ?></td>
  <td>$<?php echo number_format($p['price'],2); ?></td>
  <td><?php echo $p['inventory']; ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php require '../inc/footer.php'; ?>
