<?php
$host = 'localhost';
$db   = 'luxwear';      // <-- YOUR DB NAME
$user = 'root';
$pass = '';             // XAMPP default
$charset = 'utf8mb4';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_errno) {
    die("DB connection failed: " . $mysqli->connect_error);
}

$mysqli->set_charset($charset);
