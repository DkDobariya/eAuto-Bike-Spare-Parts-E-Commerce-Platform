<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// DB connection
$conn = new mysqli("localhost", "root", "", "eauto");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Dynamic Stats
$total_orders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$total_revenue = $conn->query("SELECT SUM(total_amount) AS revenue FROM orders")->fetch_assoc()['revenue'];
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_products = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>eAuto Admin Dashboard</title>

  <!-- Bootstrap, FontAwesome, AOS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/a2e8b6ea55.js" crossorigin="anonymous"></script>
  <link rel="icon" href="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" type="image/x-icon" />
  <style>
    html, body {
      height: 100%;
      margin: 0;
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .wrapper {
      display: flex;
      min-height: 100vh;
    }

    .main-content {
      flex-grow: 1;
      overflow-y: auto;
      padding: 20px;
      margin-left: 220px; /* Match sidebar width */
    }

    .dashboard-header {
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
    }

    .dashboard-header input {
      max-width: 250px;
    }

    .card-stat {
      border: none;
      border-radius: 12px;
      padding: 25px;
      background: linear-gradient(135deg, #ffffff, #f3f8fc);
      box-shadow: 0 4px 12px rgba(0,0,0,0.06);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-stat:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }

    .card-stat h6 {
      color: #6c757d;
      font-weight: 500;
    }

    .card-stat h3 {
      font-size: 28px;
      font-weight: bold;
      margin: 10px 0;
    }

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
        padding: 20px;
      }

      .dashboard-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
      }
    }
  </style>
</head>
<body>

<div class="wrapper">
  <!-- Sidebar -->
  <?php include 'admin-sidebar.php'; ?>

  <!-- Main Dashboard Content -->
  <div class="main-content">
    <div class="dashboard-header" data-aos="fade-down">
      <h5 class="mb-0">Welcome, <?= $_SESSION['admin_email'] ?? 'Admin' ?></h5>
      <input type="text" class="form-control" placeholder="Search something...">
    </div>

    <div class="row mt-4 g-4">
      <!-- Total Orders -->
      <div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
        <div class="card-stat">
          <h6>Total Orders</h6>
          <h3><?= number_format($total_orders) ?></h3>
          <span class="text-muted">Since last month</span>
        </div>
      </div>

      <!-- Total Revenue -->
      <div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="200">
        <div class="card-stat">
          <h6>Total Revenue</h6>
          <h3>₹<?= number_format($total_revenue ?? 0, 2) ?></h3>
          <span class="text-success">↑ 32% since last month</span>
        </div>
      </div>

      <!-- Total Users -->
      <div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="300">
        <div class="card-stat">
          <h6>Total Users</h6>
          <h3><?= number_format($total_users) ?></h3>
          <span class="text-muted">Since last month</span>
        </div>
      </div>

      <!-- Total Products -->
      <div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="400">
        <div class="card-stat">
          <h6>Total Products</h6>
          <h3><?= number_format($total_products) ?></h3>
          <span class="text-muted">All available items</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration: 700, once: true });
</script>
</body>
</html>
