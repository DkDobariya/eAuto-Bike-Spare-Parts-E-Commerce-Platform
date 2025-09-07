<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Connect to DB
$conn = new mysqli("localhost", "root", "", "eauto");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Optional filtering from GET
$where = [];
if (!empty($_GET['main_brand'])) {
    $main_brand = $conn->real_escape_string($_GET['main_brand']);
    $where[] = "main_brand = '$main_brand'";
}
if (!empty($_GET['stock_status'])) {
    $stock_status = $conn->real_escape_string($_GET['stock_status']);
    $where[] = "stock_status = '$stock_status'";
}
$where_clause = $where ? "WHERE " . implode(" AND ", $where) : "";

$result = $conn->query("SELECT * FROM products $where_clause ORDER BY id ASC");

// Set headers for Excel download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=products_export_" . date("Y-m-d") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Output table
echo "<table border='1'>";
echo "<tr>
        <th>ID</th>
        <th>Product Name</th>
        <th>Brand</th>
        <th>Main Brand</th>
        <th>Price</th>
        <th>Original Price</th>
        <th>Discount</th>
        <th>Rating</th>
        <th>Reviews</th>
        <th>Stock Status</th>
        <th>Image Filename</th>
      </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>" . htmlspecialchars($row['product_name']) . "</td>
            <td>" . htmlspecialchars($row['brand']) . "</td>
            <td>" . htmlspecialchars($row['main_brand']) . "</td>
            <td>{$row['price']}</td>
            <td>{$row['original_price']}</td>
            <td>{$row['discount_amount']}</td>
            <td>{$row['rating']}</td>
            <td>{$row['reviews']}</td>
            <td>{$row['stock_status']}</td>
            <td>" . htmlspecialchars($row['image']) . "</td>
          </tr>";
}
echo "</table>";
?>
