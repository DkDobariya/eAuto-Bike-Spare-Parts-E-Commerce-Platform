<?php
session_start();
$fullname = $_SESSION['fullname'] ?? 'Guest';

$conn = new mysqli("localhost", "root", "", "eauto");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");

$orders = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Orders | eAuto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Bootstrap & AOS CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link rel="icon" href="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" type="image/x-icon" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f5f7f9;
      margin: 0;
    }

    .sidebar {
      width: 230px;
      background-color: #11216c;
      height: 100vh;
      position: fixed;
      top: 110px;
      left: 0;
      padding: 20px;
      color: #fff;
    }

    .sidebar a {
      display: block;
      padding: 12px 15px;
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      border-radius: 4px;
      margin-bottom: 10px;
      transition: background-color 0.3s ease;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #01b8f0;
    }

    .main {
      margin-left: 230px;
      padding: 40px;
      min-height: 100vh;
    }

    .main h3 {
      color: #1b2c7a;
      margin-bottom: 30px;
    }

    .empty-box {
      font-size: 80px;
      color: #ccc;
      margin-bottom: 20px;
      animation: float 2s ease-in-out infinite;
    }

    .btn-shop {
      padding: 10px 25px;
      background-color: #00c8f8;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .btn-shop:hover {
      background-color: #009ec7;
    }

    .order-box {
      text-align: left;
      background: #fff;
      border: 1px solid #ddd;
      padding: 15px 20px;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    @keyframes float {
      0% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0); }
    }

    @media (max-width: 768px) {
      .sidebar {
        position: static;
        width: 100%;
        height: auto;
      }

      .main {
        margin-left: 0;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<!-- Sidebar -->
<div class="sidebar" data-aos="fade-right">
  <h5 class="text-center mb-4">Hello <?= htmlspecialchars(explode(" ", $fullname)[0]) ?></h5>
  <a href="my-profile.php">Profile</a>
  <a href="#" class="active">My Orders</a>
  <!-- <a href="delivery-address.php">Delivery Address</a> -->
  <a href="logout.php" class="text-danger">Log Out</a>
</div>

<!-- Main Content -->
<div class="main" data-aos="fade-up">
  <h3>My Orders</h3>

  <?php if (empty($orders)): ?>
    <div class="text-center">
      <div class="empty-box">📦</div>
      <p class="text-muted fs-5">You haven't placed any orders yet.<br>Grab it now, tomorrow it might be gone forever.</p>
      <a href="index.php" class="btn-shop">Shop now</a>
    </div>
  <?php else: ?>
    <?php foreach ($orders as $order): ?>
      <div class="order-box">
        <strong>Order #<?= $order['id'] ?></strong><br>
        <small><?= $order['created_at'] ?></small><br>
        <div class="mt-2 mb-1">Total: <strong>₹<?= number_format($order['total_amount'], 2) ?></strong></div>
        <a href="order-details.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">View Details</a>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({ duration: 700, once: true });</script>
</body>
</html>
