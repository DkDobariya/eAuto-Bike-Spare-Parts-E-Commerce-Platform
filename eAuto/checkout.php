<?php
session_start();
$conn = new mysqli("localhost", "root", "", "eauto");

// ✅ Enforce login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout");
    exit();
}

// ✅ Fetch user
$user_id = $_SESSION['user_id'];
$user_result = $conn->query("SELECT * FROM users WHERE id = $user_id LIMIT 1");
$user = $user_result->fetch_assoc();

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// ❗ Check if cart is empty
$result = $conn->query("SELECT COUNT(*) as cnt FROM cart");
$row = $result->fetch_assoc();
if ($row['cnt'] == 0) {
    header("Location: cart.php?empty=1");
    exit();
}

// ✅ Cart data processing
$cart_items = [];
$cart_count = 0;
$total = 0;

$result = $conn->query("SELECT * FROM cart");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $cart_count += $row['quantity'];
        $total += $row['quantity'] * $row['price'];
    }
}

// 5% Discount Calculation
$discount = $total * 0.05;
$final_total = $total - $discount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Checkout | eAuto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link rel="icon" href="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" type="image/x-icon" />
  <style>
    body {
      background-color: #f6f6f6;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding-top: 40px;
    }

    .checkout-container {
      max-width: 1100px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0,0,0,0.08);
      animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(20px);}
      to {opacity: 1; transform: translateY(0);}
    }

    .form-label {
      font-weight: 500;
    }

    .order-summary {
      background: #fff;
      border: 1px solid #ddd;
      padding: 20px;
      border-radius: 8px;
    }

    .order-summary img {
      width: 60px;
      height: 60px;
      object-fit: contain;
      transition: transform 0.3s;
    }

    .order-summary img:hover {
      transform: scale(1.05);
    }

    .summary-line {
      display: flex;
      justify-content: space-between;
      margin: 5px 0;
    }

    .pay-btn {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      background-color: #00c8f8;
      color: white;
      border: none;
      border-radius: 4px;
      transition: background-color 0.3s;
    }

    .pay-btn:hover {
      background-color: #009fcb;
    }

    .form-control, .form-select {
      transition: box-shadow 0.3s;
    }

    .form-control:focus, .form-select:focus {
      box-shadow: 0 0 0 0.2rem rgba(0, 200, 248, 0.3);
    }

    @media (max-width: 768px) {
      .checkout-container {
        padding: 20px;
      }

      .order-summary {
        margin-top: 30px;
      }
    }
  </style>
</head>
<body>

<!-- Logo -->
<div class="text-center my-3">
  <a href="index.php" target="_blank" rel="noopener noreferrer">
    <img 
      src="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" 
      alt="eAuto Logo" 
      width="80" 
      height="80" 
      style="border-radius: 8px;"
    >
  </a>
</div>

<!-- Main Content -->
<div class="container my-4">
  <div class="row g-4 checkout-container">
    <!-- Left: Form -->
    <div class="col-md-7">
      <h4 class="mb-3">Email</h4>
      <form method="POST" action="checkout-action.php">

        <!-- Displayed (disabled) and hidden email -->
        <input type="email" class="form-control mb-3" value="<?= htmlspecialchars($user['email']) ?>" disabled>
        <input type="hidden" name="email" value="<?= htmlspecialchars($user['email']) ?>">
        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

        <h4 class="mb-3">Delivery</h4>
        <div class="row g-2 mb-3">
          <div class="col-md-6">
            <label class="form-label">First name</label>
            <input type="text" name="first_name" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Last name</label>
            <input type="text" name="last_name" class="form-control" required>
          </div>
        </div>

        <input type="text" name="address1" class="form-control mb-3" placeholder="Flat no., Building, Apartment" required>
        <input type="text" name="address2" class="form-control mb-3" placeholder="Area, Colony, Landmark">

        <div class="row g-2 mb-3">
          <div class="col-md-4">
            <input type="text" name="city" class="form-control" placeholder="City" required>
          </div>
          <div class="col-md-4">
            <select class="form-select" name="state" required>
              <option value="Gujarat" selected>Gujarat</option>
              <option value="Maharashtra">Maharashtra</option>
              <option value="Delhi">Delhi</option>
            </select>
          </div>
          <div class="col-md-4">
            <input type="text" name="zip" class="form-control" placeholder="Pin Code" required>
          </div>
        </div>

        <input type="tel" name="phone" class="form-control mb-3" placeholder="Phone number" required>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="saveInfo">
          <label class="form-check-label" for="saveInfo">Save this information for next time</label>
        </div>

        <h4 class="mb-3">Payment</h4>
        <div class="form-check mb-4">
          <input class="form-check-input" type="radio" name="paymentMethod" id="cod" value="cod" checked>
          <label class="form-check-label" for="cod">Cash on Delivery (COD)</label>
        </div>
        <div class="form-check mb-4">
          <input class="form-check-input" type="radio" name="paymentMethod" id="online" value="online">
          <label class="form-check-label" for="online">Online Payment</label>
        </div>

        <button type="submit" class="pay-btn">Pay now</button>
      </form>
    </div>

    <!-- Right: Order Summary -->
    <div class="col-md-5 order-summary">
      <?php foreach ($cart_items as $item): ?>
        <div class="d-flex mb-3">
          <img src="http://localhost:8080/eAuto/Admin/uploads/<?= htmlspecialchars($item['image']) ?>" class="me-2" />
          <div>
            <strong><?= $item['product_name'] ?></strong><br>
            <small><?= $item['brand'] ?></small><br>
            <small>Qty: <?= $item['quantity'] ?></small>
          </div>
          <div class="ms-auto fw-bold">
            ₹<?= number_format($item['price'] * $item['quantity'], 2) ?>
          </div>
        </div>
      <?php endforeach; ?>
      <hr>
      <div class="summary-line"><span>Subtotal</span><span>₹<?= number_format($total, 2) ?></span></div>
      <div class="summary-line text-success"><span>Discount (5%)</span><span>- ₹<?= number_format($discount, 2) ?></span></div>
      <div class="summary-line"><span>Shipping</span><span>Free</span></div>
      <hr>
      <div class="summary-line fw-bold fs-5">
        <span>Total</span>
        <span>₹<?= number_format($final_total, 2) ?></span>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
