<?php
session_start();
$conn = new mysqli("localhost", "root", "", "eauto");

if (isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    $conn->query("DELETE FROM cart WHERE id = $id");
    header("Location: cart.php");
    exit();
}

if (isset($_POST['updateQty'])) {
    $id = intval($_POST['id']);
    $qty = intval($_POST['qty']);
    if ($qty > 0) {
        $conn->query("UPDATE cart SET quantity = $qty WHERE id = $id");
    }
    exit();
}

$items = $conn->query("SELECT * FROM cart");
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Cart | eAuto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  
  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <link rel="icon" href="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" type="image/x-icon" />
  <style>
    html, body {
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      background-color: #f9fbfd;
      font-family: Arial, sans-serif;
    }

    .container {
      padding: 30px 15px;
    }

    .table td, .table th {
      vertical-align: middle;
    }

    .qty-controls {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .qty-controls input {
      width: 50px;
      text-align: center;
      transition: background-color 0.3s ease;
    }

    .qty-controls input:focus {
      background-color: #eef6ff;
    }

    .cart-card, .total-card, .guarantee {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 20px;
      box-shadow: 0 0 5px rgba(0,0,0,0.05);
    }

    .remove-link {
      font-size: 12px;
      color: red;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .remove-link:hover {
      color: #b30000;
    }

    @media (max-width: 576px) {
      .qty-controls {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
  <h3 class="fw-bold mb-4 text-center text-md-start" data-aos="fade-down">🛒 My Cart</h3>

  <?php if (isset($_GET['empty'])): ?>
    <div class="alert alert-warning text-center">Your cart is empty! Please add items before checking out.</div>
  <?php endif; ?>

  <div class="row">
    <!-- Cart Items -->
    <div class="col-md-8">
      <div class="cart-card" data-aos="fade-right">
      <?php if ($items->num_rows > 0): ?>
        <table class="table table-borderless">
          <thead class="table-light">
            <tr>
              <th>Product</th>
              <th style="width: 180px;">Quantity</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
          <?php while ($row = $items->fetch_assoc()):
            $subtotal = $row['quantity'] * $row['price'];
            $total += $subtotal;
          ?>
            <tr data-aos="fade-up">
              <td>
                <div class="d-flex align-items-center gap-3">
                  <img src="http://localhost:8080/eAuto/Admin/uploads/<?= htmlspecialchars($row['image']) ?>" width="70" height="70" class="border rounded">
                  <div>
                    <small class="text-uppercase text-muted fw-semibold"><?= htmlspecialchars($row['brand']) ?></small><br>
                    <strong><?= htmlspecialchars($row['product_name']) ?></strong><br>
                    <span class="text-primary fw-semibold">Rs. <?= number_format($row['price'], 2) ?></span>
                  </div>
                </div>
              </td>
              <td>
                <div class="qty-controls">
                  <button class="btn btn-outline-secondary btn-sm" onclick="updateQty(<?= $row['id'] ?>, <?= $row['quantity'] - 1 ?>)">−</button>
                  <input type="text" readonly value="<?= $row['quantity'] ?>" id="qty<?= $row['id'] ?>">
                  <button class="btn btn-outline-secondary btn-sm" onclick="updateQty(<?= $row['id'] ?>, <?= $row['quantity'] + 1 ?>)">+</button>
                </div>
                <a href="?remove=<?= $row['id'] ?>" class="remove-link d-block mt-2">Remove</a>
              </td>
              <td><strong>Rs. <?= number_format($subtotal, 2) ?></strong></td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="text-center p-4">
          <h5 class="text-muted">Your cart is empty</h5>
          <a href="index.php" class="btn btn-outline-primary mt-3">Continue Shopping</a>
        </div>
      <?php endif; ?>
      </div>
    </div>

    <!-- Cart Summary -->
    <div class="col-md-4">
      <div class="total-card" data-aos="fade-left">
        <h5 class="fw-bold d-flex justify-content-between text-primary">
          <span>Total</span>
          <span>Rs. <?= number_format($total, 2) ?></span>
        </h5>

        <!-- Order Instructions -->
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center">
            <label class="form-label fw-semibold text-primary mb-0">Order instructions</label>
            <button type="button" class="btn btn-link p-0 text-decoration-none text-primary" onclick="toggleInstructions()">
              <span id="toggleIcon">▾</span>
            </button>
          </div>
          <div id="instructionBox" style="display: none;">
            <textarea class="form-control border-info" rows="4" placeholder="Leave your delivery instructions here..."></textarea>
            <button class="btn btn-primary mt-2">Save</button>
          </div>
        </div>

        <p class="text-muted small">
          Tax included. <a href="#">Shipping</a> calculated at checkout.
        </p>

        <?php if ($items->num_rows > 0): ?>
          <a href="checkout.php" class="btn btn-info w-100 text-white">Checkout</a>
        <?php else: ?>
          <button class="btn btn-secondary w-100" disabled>Checkout</button>
        <?php endif; ?>
      </div>

      <div class="guarantee text-center" data-aos="zoom-in">
        <p class="fw-semibold text-primary mb-1">Over 100,000+ Happy Customers</p>
        <p class="text-muted"><i class="bi bi-lock"></i> 100% Secure Payments</p>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

<!-- Bootstrap + AOS Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({ duration: 800, once: true });

function updateQty(id, qty) {
  if (qty < 1) return;
  fetch('cart.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `updateQty=1&id=${id}&qty=${qty}`
  }).then(() => location.reload());
}

function toggleInstructions() {
  const box = document.getElementById("instructionBox");
  const icon = document.getElementById("toggleIcon");
  const isVisible = box.style.display === "block";
  box.style.display = isVisible ? "none" : "block";
  icon.textContent = isVisible ? "▾" : "▴";
}

// Fade out row when removing item
document.querySelectorAll('.remove-link').forEach(link => {
  link.addEventListener('click', e => {
    e.preventDefault();
    const row = e.target.closest('tr');
    row.style.transition = 'opacity 0.4s ease';
    row.style.opacity = 0;
    setTimeout(() => window.location.href = link.href, 400);
  });
});
</script>
</body>
</html>
