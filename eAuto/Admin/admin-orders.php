<?php
session_start();

// Redirect if admin not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$conn = new mysqli("localhost", "root", "", "eauto");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle order deletion (secured)
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin-orders.php");
    exit();
}

// Handle search
$search = $_GET['search'] ?? '';
$orders = [];

if (!empty($search)) {
    $like = "%$search%";
    $stmt = $conn->prepare("
        SELECT * FROM orders 
        WHERE fullname LIKE ? OR email LIKE ? OR phone LIKE ? OR id LIKE ?
        ORDER BY created_at DESC
    ");
    $stmt->bind_param("ssss", $like, $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
}

// Store orders in array
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Orders | eAuto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- CSS Libraries -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a2e8b6ea55.js" crossorigin="anonymous"></script>
  <link rel="icon" href="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" type="image/x-icon" />

  <style>
    body {
      background-color: #f8f9fa;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    .wrapper {
      display: flex;
      min-height: 100vh;
    }

    .main-content {
      flex-grow: 1;
      padding: 20px;
      margin-left: 220px;
    }

    .table-container {
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .table th {
      background-color: #f8f9fa !important;
      font-weight: 600;
    }

    .table-hover tbody tr:hover {
      background-color: #f1f1f1;
    }

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
        padding: 15px;
      }

      .form-control.w-25 {
        width: 100% !important;
      }

      .d-flex.justify-content-between {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 10px;
      }
    }
  </style>
</head>
<body>

<div class="wrapper">
  <?php include 'admin-sidebar.php'; ?>

  <div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
      <h3 class="mb-0">
        <?= $search ? "Search Results for: <em>" . htmlspecialchars($search) . "</em>" : "All Orders" ?>
      </h3>
      <form method="get" class="d-flex" role="search">
        <input type="text" name="search" class="form-control me-2 w-25" placeholder="Search orders..." value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-primary"><i class="fas fa-search me-1"></i>Search</button>
        <?php if (!empty($search)): ?>
          <a href="admin-orders.php" class="btn btn-outline-secondary ms-2">Reset</a>
        <?php endif; ?>
      </form>
    </div>

    <div class="table-container" data-aos="fade-up">
      <table class="table table-bordered table-hover align-middle">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Payment</th>
            <th>Total</th>
            <th>Order Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($orders) > 0): ?>
            <?php foreach ($orders as $row): ?>
              <tr>
                <td>#<?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td>
                  <?= $row['payment_method'] === 'COD' 
                      ? 'Cash on Delivery (COD)' 
                      : 'Online (UPI/Card)' ?>
                </td>
                <td>₹<?= number_format($row['total_amount'], 2) ?></td>
                <td><?= isset($row['created_at']) 
                      ? date('d M Y, h:i A', strtotime($row['created_at'])) 
                      : 'N/A' ?>
                </td>
                <td>
                  <a href="print-label.php?id=<?= $row['id'] ?>" target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                    <i class="fas fa-print"></i> Print
                  </a>
                  <a href="admin-orders.php?delete=<?= $row['id'] ?>" 
                     onclick="return confirm('Are you sure you want to delete this order?')"
                     class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-trash"></i> Delete
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="text-center text-muted py-4">
                <?= $search ? "No results for '<strong>" . htmlspecialchars($search) . "</strong>'." : "No orders found." ?>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({ duration: 600, once: true });</script>
</body>
</html>
