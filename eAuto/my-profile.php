<?php
session_start();
$conn = new mysqli("localhost", "root", "", "eauto");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}
$user_id = $_SESSION['user_id'];

// Profile update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];

    $stmt = $conn->prepare("UPDATE users SET fullname=?, email=?, contact=?, birthdate=?, gender=? WHERE id=?");
    if ($stmt) {
        $stmt->bind_param("sssssi", $fullname, $email, $contact, $birthdate, $gender, $user_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['update_success'] = "Profile updated successfully.";
        header("Location: my-profile.php");
        exit();
    } else {
        die("Query failed: " . $conn->error);
    }
}

// Fetch profile data
$stmt = $conn->prepare("SELECT fullname, email, contact, birthdate, gender FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($fullname, $email, $contact, $birthdate, $gender);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Profile | eAuto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link rel="icon" href="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" type="image/x-icon" />
  <style>
    body {
      background-color: #f4f6f9;
      font-family: 'Segoe UI', sans-serif;
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
    }

    h3 {
      color: #1b2c7a;
      margin-bottom: 25px;
    }

    .form-control, .form-select {
      max-width: 500px;
    }

    .btn-save {
      background-color: #01b8f0 !important;
      color: #fff !important;
      border: none;
    }

    .btn-save:hover {
      background-color: #019ad1 !important;
      color: #fff !important;
    }

    .btn-cancel {
      background-color: #1b2c7a !important;
      color: #fff !important;
      border: none;
    }

    .btn-cancel:hover {
      background-color: #0d1a4f !important;
      color: #fff !important;
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
  <h5 class="text-center mb-4">Hello <?= htmlspecialchars(explode(" ", $_SESSION['fullname'] ?? 'Guest')[0]) ?></h5>
  <a href="my-profile.php" class="active">Profile</a>
  <a href="orders.php">My Orders</a>
  <!-- <a href="delivery-address.php">Delivery Address</a> -->
  <a href="logout.php" class="text-danger">Log Out</a>
</div>

<!-- Main Content -->
<div class="main" data-aos="fade-up">
  <h3>My Profile</h3>
  
  <?php if (isset($_SESSION['update_success'])): ?>
    <div class="alert alert-success" data-aos="fade-in">
      <?= $_SESSION['update_success']; unset($_SESSION['update_success']); ?>
    </div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($fullname) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Contact No</label>
      <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($contact) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Birthdate</label>
      <input type="date" name="birthdate" class="form-control" value="<?= $birthdate ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Gender</label>
      <select name="gender" class="form-select">
        <option value="Male" <?= ($gender == 'Male') ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= ($gender == 'Female') ? 'selected' : '' ?>>Female</option>
        <option value="Other" <?= ($gender == 'Other') ? 'selected' : '' ?>>Other</option>
      </select>
    </div>

    <button type="submit" class="btn btn-save me-2">Save</button>
    <a href="my-profile.php" class="btn btn-cancel">Cancel</a>
  </form>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration: 700, once: true });
</script>

</body>
</html>
