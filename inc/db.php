<?php
// inc/db.php
$host = 'localhost';
$db   = 'luxwear';
$user = 'root';
$pass = ''; // XAMPP default has empty password for root
$charset = 'utf8mb4';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    die("DB connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset($charset);
