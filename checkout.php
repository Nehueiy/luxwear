<?php
require 'inc/db.php';
session_start();
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
  header('Location: cart.php'); exit;
}
// require login
if (!isset($_SESSION['user'])) {
  // store a return url then redirect to login
  $_SESSION['return_to'] = '/luxwear/checkout.php';
  header('Location: login.php'); exit;
}

$cart = $_SESSION['cart'];
$total = 0;
foreach($cart as $it) $total += $it['price'] * $it['qty'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // create order
  $user_id = $_SESSION['user']['id'];
  $stmt = $mysqli->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
  $stmt->bind_param('id', $user_id, $total);
  $stmt->execute();
  $order_id = $stmt->insert_id;

  // insert items
  $stmtItem = $mysqli->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
  foreach($cart as $it) {
    $stmtItem->bind_param('iiid', $order_id, $it['id'], $it['qty'], $it['price']);
    $stmtItem->execute();
    // optionally decrement inventory
    $upd = $mysqli->prepare("UPDATE products SET inventory = inventory - ? WHERE id = ?");
    $upd->bind_param('ii', $it['qty'], $it['id']);
    $upd->execute();
  }

  // clear cart
  unset($_SESSION['cart']);
  echo "<p>Order placed! Order ID: $order_id</p><p><a href='index.php'>Continue shopping</a></p>";
  exit;
}

require 'inc/header.php';
?>
<h1>Checkout</h1>
<p>Order total: <strong>$<?php echo number_format($total,2); ?></strong></p>
<form method="post" action="checkout.php">
  <div class="form-field">
    <label>Shipping Address</label>
    <textarea name="address" required></textarea>
  </div>
  <button class="btn" type="submit">Place Order (simulate)</button>
</form>
<?php require 'inc/footer.php'; ?>
