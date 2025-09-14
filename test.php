<?php
require 'inc/db.php';
$res = $mysqli->query("SELECT * FROM products");
if(!$res){ die("Query error: " . $mysqli->error); }
$products = $res->fetch_all(MYSQLI_ASSOC);
echo "<pre>"; print_r($products); echo "</pre>";

