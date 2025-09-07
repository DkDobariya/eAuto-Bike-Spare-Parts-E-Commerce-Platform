<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$conn = new mysqli("localhost", "root", "", "eauto");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>eAuto Header</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
  body {
    margin: 0;
    padding-top: 100px;
    font-family: Arial, sans-serif;
  }

  .top-banner {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background-color: #00c8f8;
    color: white;
    padding: 6px 15px;
    font-size: 14px;
    font-weight: 500;
    z-index: 1040;
  }

  .navbar-custom {
    background-color: #1b2c7a;
    padding: 12px 20px;
    color: white;
    z-index: 1030;
  }

  .search-bar {
    display: flex;
    width: 520px;
    height: 38px;
    border-radius: 4px;
    overflow: hidden;
    border: 2px solid #1b2c7a;
    background-color: white;
    transition: box-shadow 0.3s ease;
  }

  .search-bar:hover,
  .search-input:focus {
    box-shadow: 0 0 0 2px rgba(0, 178, 226, 0.4);
  }

  .search-input {
    flex: 1;
    border: none;
    padding: 0 10px;
    font-size: 14px;
    outline: none;
  }

  .search-category {
    border: none;
    padding: 0 10px;
    background-color: white;
    font-size: 14px;
    color: #1b2c7a;
    border-left: 1px solid #ccc;
    outline: none;
  }

  .search-button {
    background-color: #00b2e2;
    color: white;
    border: none;
    width: 50px;
    font-size: 18px;
    cursor: pointer;
  }

  .account-cart-section {
    display: flex;
    align-items: center;
    gap: 20px;
    position: relative;
  }

  .cart {
    display: flex;
    align-items: center;
    position: relative;
    cursor: pointer;
  }

  .cart-badge {
    position: absolute;
    top: -5px;
    left: 12px;
    background-color: #00c8f8;
    color: white;
    font-size: 12px;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 50%;
  }

  .cart-text {
    margin-left: 30px;
    font-size: 16px;
  }

  .cart-dropdown {
    position: absolute;
    top: 50px;
    right: 0;
    width: 350px;
    max-height: 400px;
    overflow-y: auto;
    background: white;
    color: #212529;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #ccc;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: none;
    z-index: 999;
    animation: slideDown 0.3s ease-in-out;
  }

  @keyframes slideDown {
    from {
      transform: translateY(-10px);
      opacity: 0;
    }
    to {
      transform: translateY(0px);
      opacity: 1;
    }
  }

  .cart-dropdown::-webkit-scrollbar {
    width: 6px;
  }

  .cart-dropdown::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 5px;
  }

  .cart-dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
  }

  .cart-dropdown-item img {
    width: 60px;
    height: 60px;
    object-fit: contain;
  }

  .divider {
    width: 1px;
    height: 30px;
    background-color: rgba(255, 255, 255, 0.4);
  }

  .dropdown-menu {
    animation: fadeIn 0.25s ease-in-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  @media (max-width: 768px) {
    .search-bar {
      width: 100%;
      margin-top: 10px;
    }

    .account-cart-section {
      flex-direction: column;
      gap: 10px;
    }
  }
</style>
</head>
<body>

<!-- Top Banner -->
<div class="top-banner">
  <div class="container-fluid d-flex justify-content-between">
    <div>🚚 <strong>Prepaid = 5% OFF + Priority Super-fast Shipping!</strong></div>
    <a href="index.php" class="subscribe text-white text-decoration-none fw-bold">
      <i class="fas fa-envelope"></i> Subscribe & Save
    </a>
  </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom fixed-top" style="top: 38px;">
  <div class="container-fluid px-4">
    <a class="navbar-brand text-white fw-bold" href="index.php">eAuto ⚙</a>

    <!-- Search -->
    <div class="search-bar ms-3">
      <input type="text" placeholder="Search..." class="search-input" />
      <select class="search-category" id="categorySelect">
        <option>All categories</option>
        <option>Bajaj</option>
        <option>Hero</option>
        <option>Honda</option>
      </select>
      <button class="search-button" id="searchBtn">🔍</button>
    </div>

    <!-- Account & Cart -->
    <div class="account-cart-section ms-auto">

      <!-- 👤 User Profile or Login -->
      <div class="account-links text-white dropdown">
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['fullname'])): ?>
          <?php
            $firstName = explode(' ', $_SESSION['fullname'])[0];
          ?>
          <a href="#" class="dropdown-toggle text-white text-decoration-none fw-bold" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            👤 <?= htmlspecialchars($firstName) ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="my-profile.php">My Profile</a></li>
            <li><a class="dropdown-item" href="orders.php">My Orders</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
          </ul>
        <?php else: ?>
          <a href="login.php" class="text-white text-decoration-none fw-bold">Login / Signup</a>
        <?php endif; ?>
      </div>

      <!-- Divider -->
      <div class="divider d-none d-md-block"></div>

      <!-- 🛒 Cart -->
      <div class="cart" id="cartIcon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 24 24">
          <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2S15.9 22 17 22s2-.9 2-2-.9-2-2-2zm-9.83-4l.6-1h7.45c.75 0 1.41-.41 1.75-1.03L21 6H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 17.37 5.48 19 7 19h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12z"/>
        </svg>
        <span class="cart-badge"><?= $cart_count ?></span>
        <span class="cart-text">Cart</span>

        <!-- Dropdown -->
        <div class="cart-dropdown" id="cartDropdown">
          <?php if (empty($cart_items)): ?>
            <div class="text-center">
              <!-- SVG Cart Icon -->
              <svg viewBox="0 0 100 100" width="80" height="80" style="margin-bottom: 10px;">
                <g transform="translate(0 2)" stroke-width="4" stroke="#1e2d7d" fill="none" stroke-linecap="square" fill-rule="evenodd">
                  <circle cx="34" cy="60" r="6"></circle>
                  <circle cx="67" cy="60" r="6"></circle>
                  <path d="M22.9360352 15h54.8070373l-4.3391876 30H30.3387146L19.6676025 0H.99560547"></path>
                </g>
              </svg>

              <p class="fw-bold mt-2">Your cart is empty</p>
              <a href="index.php" class="btn btn-info text-white">Shop our products</a>
            </div>
          <?php else: ?>
            <?php foreach ($cart_items as $item): ?>
              <div class="cart-dropdown-item d-flex gap-2">
                <img src="http://localhost:8080/eAuto/Admin/uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" width="60" height="60" style="object-fit: cover;">
                <div>
                  <div class="fw-bold"><?= $item['product_name'] ?></div>
                  <div class="text-muted"><?= $item['brand'] ?></div>
                  <div class="text-muted">Qty: <?= $item['quantity'] ?> × Rs. <?= number_format($item['price'], 2) ?></div>
                  <div class="fw-semibold">Rs. <?= number_format($item['quantity'] * $item['price'], 2) ?></div>
                </div>
              </div>
            <?php endforeach; ?>
            <hr>
            <div class="d-flex justify-content-between fw-bold">
              <span>Total</span>
              <span>Rs. <?= number_format($total, 2) ?></span>
            </div>
            <div class="d-grid gap-2 mt-3">
              <a href="cart.php" class="btn btn-primary btn-sm fw-bold">View cart</a>
              <?php if ($cart_count > 0): ?>
                <a href="checkout.php" class="btn btn-info text-white btn-sm fw-bold">Checkout</a>
              <?php else: ?>
                <button class="btn btn-info text-white btn-sm fw-bold" disabled>Checkout</button>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</nav>

<script>
  document.getElementById('searchBtn').addEventListener('click', function () {
    const category = document.getElementById('categorySelect').value;
    const map = {
      'Bajaj': 'Bajaj.php',
      'Hero': 'Hero.php',
      'Honda': 'Honda.php'
    };
    if (map[category]) {
      window.location.href = map[category];
    } else {
      alert('Please select a valid category.');
    }
  });

  // Toggle cart dropdown with animation
  const cartIcon = document.getElementById('cartIcon');
  const cartDropdown = document.getElementById('cartDropdown');

  cartIcon.addEventListener('click', () => {
    if (cartDropdown.style.display === 'block') {
      cartDropdown.style.display = 'none';
    } else {
      cartDropdown.style.display = 'block';
    }
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', (e) => {
    if (!cartIcon.contains(e.target) && !cartDropdown.contains(e.target)) {
      cartDropdown.style.display = 'none';
    }
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
