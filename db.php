<?php
// db.php - update DB credentials if needed
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = ''; // XAMPP default
$DB_NAME = 'pets_shop';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");
?>