<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "eauto");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email is already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("sss", $fullname, $email, $hashed_password);
            if ($stmt->execute()) {
                header("Location: login.php?success=1");
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | eAuto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- AOS Animation -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
  <link rel="icon" href="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" type="image/x-icon" />
  <style>
    body {
      background: #f5f7fa;
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .container {
      max-width: 500px;
      margin: auto;
      padding: 40px 15px;
      flex: 1;
    }

    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 12px 30px rgba(0,0,0,0.08);
      padding: 30px;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: scale(1.01);
    }

    .form-control {
      border-radius: 6px;
      padding: 12px;
      font-size: 15px;
    }

    .btn-primary {
      background-color: #00c8f8;
      border: none;
      padding: 12px;
      font-size: 16px;
      font-weight: 600;
      border-radius: 6px;
      transition: background-color 0.3s;
    }

    .btn-primary:hover {
      background-color: #009ec7;
    }

    .text-link {
      font-size: 14px;
    }

    .text-link a {
      color: #00c8f8;
      text-decoration: none;
    }

    .text-link a:hover {
      text-decoration: underline;
    }

    .alert {
      font-size: 14px;
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
  <div class="card" data-aos="fade-up">
    <h3 class="text-center mb-4">Create Your Account</h3>
    
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST">
      <div class="mb-3">
        <input type="text" name="fullname" class="form-control" placeholder="Full Name" required>
      </div>
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email Address" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <div class="mb-3">
        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Register</button>
      <p class="text-center mt-3 text-link">Already have an account? <a href="login.php">Login</a></p>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
  AOS.init();
</script>

</body>
</html>
