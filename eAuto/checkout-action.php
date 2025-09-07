<?php
session_start();
$conn = new mysqli("localhost", "root", "", "eauto");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch cart items
$cart_items = [];
$total = 0;
$result = $conn->query("SELECT * FROM cart");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total += $row['quantity'] * $row['price'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $fullname = $first_name . ' ' . $last_name;
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address1'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $zip = trim($_POST['zip'] ?? '');
    $payment_method = ($_POST['paymentMethod'] ?? 'cod') === 'cod' ? 'COD' : 'Online';
    $final_total = round($total * 0.95, 2); // Apply 5% discount
    $user_id = $_SESSION['user_id'] ?? 0;

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($address) || empty($city) || empty($state) || empty($zip)) {
        die("❌ Please fill all required fields.");
    }

    // Insert into orders table
    $stmt = $conn->prepare("INSERT INTO orders (user_id, fullname, email, phone, address, city, state, zip, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Order INSERT failed: " . $conn->error);
    }
    $stmt->bind_param("issssssssd", $user_id, $fullname, $email, $phone, $address, $city, $state, $zip, $payment_method, $final_total);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert order items
    foreach ($cart_items as $item) {
        $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_name, brand, quantity, price, product_image) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt_item) {
            die("Item INSERT failed: " . $conn->error);
        }
        $stmt_item->bind_param("issids", $order_id, $item['product_name'], $item['brand'], $item['quantity'], $item['price'], $item['image']);
        $stmt_item->execute();
    }

    // Clear the cart
    $conn->query("DELETE FROM cart");

    // Redirect to order success page
    header("Location: order-success.php?id=$order_id");
    exit();
} else {
    echo "<h3 style='color:red; text-align:center; margin-top:50px;'>❌ Invalid request. Please try again.</h3>";
    exit;
}
?>
