<?php
require 'inc/db.php';
require 'inc/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $mysqli->prepare("SELECT p.*, c.name as category FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->bind_param('i',$id);
$stmt->execute();
$res = $stmt->get_result();
$p = $res->fetch_assoc();
if (!$p) {
  echo "<h2>Product not found</h2>";
  require 'inc/footer.php';
  exit;
}
?>
<div class="card">
  <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['title']); ?>">
  <h1><?php echo htmlspecialchars($p['title']); ?></h1>
  <p><?php echo nl2br(htmlspecialchars($p['description'])); ?></p>
  <div><strong>$<?php echo number_format($p['price'],2); ?></strong></div>
  <form method="post" action="add_to_cart.php">
    <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
    <input type="number" name="qty" value="1" min="1" max="<?php echo $p['inventory']; ?>" style="width:80px;">
    <button class="btn" type="submit">Add to cart</button>
  </form>
</div>

<?php require 'inc/footer.php'; ?>
