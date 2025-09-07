<?php
session_start();
$conn = new mysqli("localhost", "root", "", "eauto");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_name = $_POST['product_name'] ?? '';
    $price = $_POST['price'] ?? '';
    $image = $_POST['image'] ?? '';
    $brand = $_POST['brand'] ?? ''; // <-- Make sure the name matches

    $quantity = 1;

    $stmt = $conn->prepare("SELECT id FROM cart WHERE product_name = ?");
    $stmt->bind_param("s", $product_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $update = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE product_name = ?");
        $update->bind_param("s", $product_name);
        $update->execute();
    } else {
        $insert = $conn->prepare("INSERT INTO cart (product_name, price, image, brand, quantity) VALUES (?, ?, ?, ?, ?)");
        $insert->bind_param("sdssi", $product_name, $price, $image, $brand, $quantity);
        $insert->execute();
    }

    header("Location: cart.php");
    exit();
}
?>
