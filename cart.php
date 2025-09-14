<?php
require 'inc/db.php';
require 'inc/header.php';
$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>
<h1>Your Cart</h1>
<?php if(empty($cart)): ?>
  <p>Your cart is empty. <a href="index.php">Continue shopping</a></p>
<?php else: ?>
  <table style="width:100%; background:#fff; padding:10px;">
    <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
    <?php foreach($cart as $i => $item): 
      $subtotal = $item['price'] * $item['qty'];
      $total += $subtotal;
    ?>
    <tr>
      <td><?php echo htmlspecialchars($item['title']); ?></td>
      <td>$<?php echo number_format($item['price'],2); ?></td>
      <td><?php echo $item['qty']; ?></td>
      <td>$<?php echo number_format($subtotal,2); ?></td>
    </tr>
    <?php endforeach; ?>
    <tr><td colspan="3" style="text-align:right;"><strong>Total</strong></td><td><strong>$<?php echo number_format($total,2); ?></strong></td></tr>
  </table>
  <div style="margin-top:12px;">
    <a class="btn" href="checkout.php">Proceed to Checkout</a>
  </div>
<?php endif; ?>
<?php require 'inc/footer.php'; ?>
