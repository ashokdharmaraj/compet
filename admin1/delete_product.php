<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
require '../db.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: index.php');
    exit;
}

// Get image filename to delete it
$stmt = $mysqli->prepare("SELECT image FROM products WHERE id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($image);
$stmt->fetch();
$stmt->close();

// Delete from DB
$stmt = $mysqli->prepare("DELETE FROM products WHERE id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

// Delete image file
if ($image && file_exists(__DIR__ . "/uploads/" . $image)) {
    unlink(__DIR__ . "/uploads/" . $image);
}

header('Location: index.php');
exit;
