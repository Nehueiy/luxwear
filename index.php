<?php
require 'inc/db.php';
require 'inc/header.php';

// Fetch products with their category name
$sql = "
    SELECT p.id, p.name, p.price, p.image, c.name AS category
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.created_at DESC
";

$res = $mysqli->query("SELECT p.*, c.name as category FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC");
if(!$res){
    die("Query failed: " . $mysqli->error);
}
$products = $res->fetch_all(MYSQLI_ASSOC);

?>

<h2>Products</h2>
<div class="product-list">
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $p): ?>
            <div class="product">
                <img src="assets/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                <h3><?= htmlspecialchars($p['name']) ?></h3>
                <p>$<?= number_format($p['price'], 2) ?></p>
                <p><small><?= htmlspecialchars($p['category']) ?></small></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>
</div>

<?php require 'inc/footer.php'; ?>
