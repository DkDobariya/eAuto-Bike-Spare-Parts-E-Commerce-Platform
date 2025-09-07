<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login | eAuto</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- AOS Animation CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet" />
  <link rel="icon" href="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" type="image/x-icon" />
  <style>
    body {
      background-color: #f5f5f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .login-container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 50px 15px;
    }

    .login-panel {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 12px;
      max-width: 420px;
      width: 100%;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease-in-out;
    }

    .login-panel:hover {
      transform: scale(1.01);
    }

    .login-panel h2 {
      margin-bottom: 20px;
      color: #1b2c7a;
    }

    .input-field {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ced4da;
      border-radius: 6px;
      font-size: 15px;
      transition: border-color 0.3s;
    }

    .input-field:focus {
      border-color: #1b2c7a;
      outline: none;
    }

    .login-button {
      width: 100%;
      padding: 12px;
      background-color: #00c8f8;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      font-size: 16px;
      transition: background-color 0.3s;
    }

    .login-button:hover {
      background-color: #009ec7;
    }

    .links {
      margin-top: 15px;
      font-size: 14px;
    }

    .links a {
      color: #00c8f8;
      text-decoration: none;
    }

    .links a:hover {
      text-decoration: underline;
    }

    .alert {
      font-size: 14px;
      padding: 10px;
    }

    @media (max-width: 576px) {
      .login-panel {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="login-container">
  <div class="login-panel" data-aos="fade-up" data-aos-delay="100">
    <h2>Login to your account</h2>

    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success text-center">Account created successfully! Please log in.</div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger text-center"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <form method="POST" action="login-action.php">
      <input type="email" name="email" placeholder="Email" class="input-field" required />
      <input type="password" name="password" placeholder="Password" class="input-field" required />
      <button type="submit" class="login-button">Login</button>
    </form>

    <div class="links">
      New user? <a href="registration.php">Create your account</a><br>
      Admin? <a href="Admin/login.php" target="_blank">Login here</a>
    </div>
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
