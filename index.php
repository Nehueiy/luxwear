<?php
require 'inc/db.php';
require 'inc/header.php';

// Fetch products
$res = $mysqli->query("SELECT p.*, c.name as category FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC");

if (!$res) {
    die("Query Failed: " . $mysqli->error);
}

$products = $res->fetch_all(MYSQLI_ASSOC);
?>

<h1>Products</h1>

<?php if (!empty($products)): ?>
<div class="product-grid">
<?php foreach($products as $p): ?>
  <div class="card">
    <a href="product.php?id=<?php echo $p['id']; ?>">
      <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['title']); ?>">
    </a>
    <h3><?php echo htmlspecialchars($p['title']); ?></h3>
    <div>$<?php echo number_format((float)$p['price'],2); ?></div>
  </div>
<?php endforeach; ?>
</div>
<?php else: ?>
<p>No products found. Check your database.</p>
<?php endif; ?>

<?php
// ===== Recommendations (for demo, using first product) =====
$recommendations = [];
if (!empty($products)) {
    $product_id = $products[0]['id'];
    $cmd = "python recommend.py " . escapeshellarg($product_id);
    $output = shell_exec($cmd);
    $recommendations = json_decode($output, true);
}
?>

<h3>You may also like:</h3>
<div class="recommendations">
  <?php if(!empty($recommendations)): ?>
    <?php foreach($recommendations as $rec): ?>
      <div class="item">
        <img src="<?php echo htmlspecialchars($rec['image']); ?>" width="120">
        <p><?php echo htmlspecialchars($rec['title']); ?></p>
        <p>$<?php echo number_format((float)$rec['price'],2); ?></p>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No recommendations found.</p>
  <?php endif; ?>
</div>

<?php require 'inc/footer.php'; ?>
