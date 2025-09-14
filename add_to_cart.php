<?php
session_start();
require 'inc/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$product_id = (int)($_POST['product_id'] ?? 0);
$qty = max(1, (int)($_POST['qty'] ?? 1));

// fetch product for validation
$stmt = $mysqli->prepare("SELECT id, title, price, inventory, image FROM products WHERE id = ?");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$res = $stmt->get_result();
$product = $res->fetch_assoc();
if (!$product) {
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
        $item['qty'] += $qty;
        $found = true;
        break;
    }
}
if (!$found) {
    $_SESSION['cart'][] = [
        'id' => $product['id'],
        'title' => $product['title'],
        'price' => $product['price'],
        'image' => $product['image'],
        'qty' => $qty
    ];
}

header('Location: cart.php');
exit;
