<?php
session_start();
$conn = new mysqli("localhost", "root", "", "eauto");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch fullname from database if not set in session
if (!isset($_SESSION['fullname'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($fullname);
    if ($stmt->fetch()) {
        $_SESSION['fullname'] = $fullname;
    } else {
        $_SESSION['fullname'] = "User";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Delivery Address | eAuto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a2e8b6ea55.js" crossorigin="anonymous"></script>
  <link rel="icon" href="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" type="image/x-icon" />
  <style>
    body {
      background-color: #f4f6f8;
      font-family: 'Segoe UI', sans-serif;
      padding-top: 110px;
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
      margin-bottom: 8px;
      transition: background 0.3s ease;
    }
    .sidebar a:hover,
    .sidebar a.active {
      background-color: #01b8f0;
    }
    .main {
      margin-left: 230px;
      padding: 40px;
      background-color: #f4f6f8;
    }
    .address-box {
      border: 2px dashed #ccc;
      padding: 60px 20px;
      text-align: center;
      background-color: #f9fbfd;
      cursor: pointer;
      color: #1b2c7a;
      transition: all 0.3s ease;
      animation: float 2.5s ease-in-out infinite;
    }
    .address-box:hover {
      background-color: #eef4fb;
    }
    .address-box i {
      font-size: 36px;
      margin-bottom: 10px;
      color: #ccc;
    }
    .form-section {
      background: #fff;
      padding: 25px;
      border-radius: 8px;
      box-shadow: 0 0 8px rgba(0,0,0,0.05);
      margin-top: 30px;
    }
    .btn-cancel {
      background-color: #1b2c7a !important;
      color: white !important;
    }
    .btn-save {
      background-color: #01b8f0 !important;
      color: white !important;
    }
    @keyframes float {
      0% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0); }
    }
    @media (max-width: 768px) {
      .sidebar {
        position: relative;
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
  <h5 class="text-center mb-4">Hello <?= htmlspecialchars(explode(" ", $_SESSION['fullname'])[0]) ?></h5>
  <a href="my-profile.php">Profile</a>
  <a href="orders.php">My Orders</a>
  <a href="delivery-address.php" class="active">Delivery Address</a>
  <a href="logout.php" class="text-danger">Log Out</a>
</div>

<!-- Layout -->
<div class="container-fluid">
  <div class="row">
    <div class="main col-md-8 col-12" data-aos="fade-up">
      <!-- Add Address Box -->
      <div id="add-address-box" class="address-box rounded mb-4">
        <i class="fas fa-plus-circle"></i><br>
        <strong>Add a New Address</strong>
      </div>

      <!-- Add Address Form -->
      <div id="address-form-section" class="form-section" style="display: none;">
        <form method="post">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">First Name</label>
              <input type="text" class="form-control" name="first_name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Last Name</label>
              <input type="text" class="form-control" name="last_name">
            </div>
            <div class="col-md-12">
              <label class="form-label">Address Line 1</label>
              <input type="text" class="form-control" name="address1">
            </div>
            <div class="col-md-12">
              <label class="form-label">Address Line 2</label>
              <input type="text" class="form-control" name="address2">
            </div>
            <div class="col-md-4">
              <label class="form-label">Company</label>
              <input type="text" class="form-control" name="company">
            </div>
            <div class="col-md-4">
              <label class="form-label">Postal Code</label>
              <input type="text" class="form-control" name="postal">
            </div>
            <div class="col-md-4">
              <label class="form-label">Phone</label>
              <input type="text" class="form-control" name="phone">
            </div>
            <div class="col-md-4">
              <label class="form-label">City</label>
              <input type="text" class="form-control" name="city">
            </div>
            <div class="col-md-4">
              <label class="form-label">Country</label>
              <select class="form-select" name="country">
                <option selected>India</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Province</label>
              <select class="form-select" name="province">
                <option>Gujarat</option>
                <option>Maharashtra</option>
                <option>Rajasthan</option>
                <option>Tamil Nadu</option>
              </select>
            </div>
            <div class="col-md-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_default" id="defaultAddress">
                <label class="form-check-label" for="defaultAddress">Set as default address</label>
              </div>
            </div>
            <div class="col-md-12 text-end">
              <button type="button" id="cancel-button" class="btn btn-cancel me-2">Cancel</button>
              <button type="submit" class="btn btn-save">Add Address</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration: 700, once: true });

  document.getElementById("add-address-box").addEventListener("click", () => {
    document.getElementById("address-form-section").style.display = "block";
    document.getElementById("add-address-box").style.display = "none";
  });
  document.getElementById("cancel-button").addEventListener("click", () => {
    document.getElementById("address-form-section").style.display = "none";
    document.getElementById("add-address-box").style.display = "block";
  });
</script>
</body>
</html>
