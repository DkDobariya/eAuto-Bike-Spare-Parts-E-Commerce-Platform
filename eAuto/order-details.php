<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// DB connection
$conn = new mysqli("localhost", "root", "", "eauto");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<h2>Invalid request. Please try again.</h2>";
    exit;
}

$order_id = intval($_GET['id']);

// Fetch order info
$order_result = $conn->query("SELECT * FROM orders WHERE id = $order_id LIMIT 1");
if ($order_result->num_rows === 0) {
    echo "<h2>Order not found.</h2>";
    exit;
}
$order = $order_result->fetch_assoc();

// Fetch order items
$items_result = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order #<?= $order['id'] ?> Details | eAuto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- AOS Animate On Scroll -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
  <link rel="icon" href="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" type="image/x-icon" />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .container {
      max-width: 900px;
      margin-top: 40px;
    }

    .product-img {
      width: 70px;
      height: 70px;
      object-fit: cover;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .total-box {
      background: #fff;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 8px;
      margin-top: 20px;
    }

    .order-heading {
      color: #1b2c7a;
      font-weight: 600;
    }

    @media (max-width: 768px) {
      .product-img {
        width: 50px;
        height: 50px;
      }
    }
  </style>
</head>
<body>
<div class="container bg-white p-4 rounded shadow" data-aos="fade-up">
  <h3 class="order-heading mb-4">Order #<?= $order['id'] ?> Details</h3>

  <div class="mb-3">
    <strong>Customer:</strong> <?= htmlspecialchars($order['fullname']) ?><br>
    <strong>Email:</strong> <?= htmlspecialchars($order['email']) ?><br>
    <strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?><br>
    <strong>Address:</strong> <?= htmlspecialchars($order['address']) ?>, <?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['state']) ?> - <?= htmlspecialchars($order['zip']) ?><br>
    <strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?><br>
    <strong>Placed on:</strong> <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?>
  </div>

  <table class="table table-bordered table-hover">
    <thead class="table-light">
      <tr>
        <th>Image</th>
        <th>Product</th>
        <th>Brand</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php $grand_total = 0; ?>
      <?php while ($item = $items_result->fetch_assoc()): ?>
        <tr>
          <td>
            <img src="http://localhost:8080/eAuto/Admin/uploads/<?php echo htmlspecialchars($item['product_image']); ?>" 
            class="product-img" 
            alt="<?php echo htmlspecialchars($item['product_name']); ?>">
          </td>
          <td><?= htmlspecialchars($item['product_name']) ?></td>
          <td><?= htmlspecialchars($item['brand']) ?></td>
          <td><?= $item['quantity'] ?></td>
          <td>₹<?= number_format($item['price'], 2) ?></td>
          <td>₹<?= number_format($item['quantity'] * $item['price'], 2) ?></td>
        </tr>
        <?php $grand_total += $item['quantity'] * $item['price']; ?>
      <?php endwhile; ?>
    </tbody>
  </table>

  <?php $discounted_total = $grand_total * 0.95; // Apply 5% discount ?>
  <div class="total-box text-end">
    <h5>Grand Total: ₹<?= number_format($grand_total, 2) ?></h5>
    <h5 class="text-success">Total Paid (5% Discount Applied): ₹<?= number_format($discounted_total, 2) ?></h5>
    <a href="index.php" class="btn btn-primary mt-4">Continue Shopping</a>
  </div>
</div>

<!-- Bootstrap JS and AOS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>

</body>
</html>
