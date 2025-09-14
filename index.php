<?php
require 'inc/db.php';
require 'inc/header.php';

$res = $mysqli->query("SELECT p.*, c.name as category FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC");
$products = $res->fetch_all(MYSQLI_ASSOC);
?>
<h1>Products</h1>
<div class="product-grid">
<?php foreach($products as $p): ?>
  <div class="card">
    <a href="product.php?id=<?php echo $p['id']; ?>">
      <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['title']); ?>">
    </a>
    <h3><?php echo htmlspecialchars($p['title']); ?></h3>
    <div>$<?php echo number_format($p['price'],2); ?></div>
    <div style="margin-top:8px;">
      <form method="post" action="add_to_cart.php">
        <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
        <input type="number" name="qty" value="1" min="1" max="<?php echo $p['inventory']; ?>" style="width:60px;">
        <button class="btn" type="submit">Add to cart</button>
      </form>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php require 'inc/footer.php'; ?>
