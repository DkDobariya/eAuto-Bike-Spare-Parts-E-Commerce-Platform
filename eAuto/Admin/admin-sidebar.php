<!-- admin-sidebar.php -->

<style>
  .sidebar {
    width: 220px;
    height: 100vh;
    background-color: #1b2c7a;
    color: white;
    padding-top: 1rem;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    z-index: 1000;
    transition: all 0.3s ease;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.08);
  }

  .sidebar h4 {
    font-size: 22px;
    margin-bottom: 1rem;
    color: #ffffff;
    font-weight: bold;
  }

  .sidebar a {
    color: #ecf0f1;
    text-decoration: none;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    transition: all 0.2s ease-in-out;
    font-weight: 500;
    font-size: 15px;
  }

  .sidebar a i {
    min-width: 20px;
    margin-right: 10px;
    transition: transform 0.3s;
  }

  .sidebar a:hover i {
    transform: scale(1.1);
  }

  .sidebar a:hover,
  .sidebar a.active {
    background-color: #00c8f8;
    border-left: 4px solid #1abc9c;
    color: #fff;
    font-weight: 600;
  }

  .sidebar a.text-danger {
    margin-top: auto;
    border-top: 1px solid rgba(255,255,255,0.1);
  }

  @media (max-width: 768px) {
    .sidebar {
      position: relative;
      width: 100%;
      height: auto;
      flex-direction: row;
      flex-wrap: wrap;
      padding: 10px;
      justify-content: space-around;
    }

    .sidebar a {
      flex: 1 1 45%;
      justify-content: center;
      margin: 5px 0;
      border-left: none !important;
      text-align: center;
      padding: 10px;
    }

    .sidebar h4 {
      display: none;
    }
  }
</style>

<div class="sidebar" data-aos="fade-right">
  <div class="text-center mb-4">
    <h4>eAuto Admin</h4>
  </div>

  <a href="admin-dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin-dashboard.php' ? 'active' : '' ?>">
    <i class="fas fa-tachometer-alt"></i>Dashboard
  </a>

  <a href="manage-products.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-products.php' ? 'active' : '' ?>">
    <i class="fas fa-motorcycle"></i>Products
  </a>

  <a href="admin-orders.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin-orders.php' ? 'active' : '' ?>">
    <i class="fas fa-shopping-cart"></i>Orders
  </a>

  <a href="admin-customers.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin-customers.php' ? 'active' : '' ?>">
    <i class="fas fa-users"></i>Customers
  </a>

  <a href="logout.php" class="text-danger">
    <i class="fas fa-sign-out-alt me-2"></i>Logout
  </a>
</div>

<!-- Include AOS if not already -->
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({ duration: 700, once: true });</script>
