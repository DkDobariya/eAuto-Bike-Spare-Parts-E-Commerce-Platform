<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage-products.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "eauto");
$id = intval($_GET['id']);

$conn->query("DELETE FROM products WHERE id = $id");
header("Location: manage-products.php");
exit();
?>
