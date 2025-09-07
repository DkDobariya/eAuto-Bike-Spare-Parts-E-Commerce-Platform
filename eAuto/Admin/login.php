<?php
session_start();
$conn = new mysqli("localhost", "root", "", "eauto");

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_email'] = $admin['email'];
        header("Location: admin-dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Login - eAuto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/a2e8b6ea55.js" crossorigin="anonymous"></script>
  <link rel="icon" href="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" type="image/x-icon" />
  <style>
    body {
      background: linear-gradient(to right, #11216c, #01b8f0);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .login-box {
      background: #fff;
      padding: 35px 30px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 400px;
    }

    .login-box h3 {
      color: #1b2c7a;
      font-weight: 600;
    }

    .btn-primary {
      background-color: #01b8f0;
      border: none;
    }

    .btn-primary:hover {
      background-color: #009fd2;
    }

    .footer-links {
      text-align: center;
      margin-top: 15px;
      font-size: 0.92rem;
    }

    .footer-links a {
      color: #01b8f0;
      text-decoration: none;
    }

    .footer-links a:hover {
      text-decoration: underline;
    }

    @media (max-width: 500px) {
      .login-box {
        padding: 25px 20px;
        margin: 10px;
      }
    }
  </style>
</head>
<body>

<div class="login-box" data-aos="fade-up">
  <h3 class="mb-3 text-center"><i class="fas fa-lock me-2"></i>Admin Login</h3>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required />
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
    <div class="footer-links mt-3">
      <span>Login as user? <a href="../Login.php">Click here</a></span><br>
      <span>New user? <a href="../registration.php">Register</a></span>
    </div>
  </form>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({ duration: 800, once: true });</script>

</body>
</html>
