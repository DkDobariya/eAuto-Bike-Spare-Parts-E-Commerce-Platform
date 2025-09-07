<?php
session_start();
$conn = new mysqli("localhost", "root", "", "eauto");

// Simulate admin login (temporary)
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['admin_id'] = 1;
}

$search = $_GET['search'] ?? '';

if (!empty($search)) {
    $stmt = $conn->prepare("SELECT id, fullname, email, contact, birthdate, gender FROM users WHERE fullname LIKE ? OR email LIKE ? ORDER BY id DESC");
    $like = "%" . $search . "%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $result = $conn->query("SELECT id, fullname, email, contact, birthdate, gender FROM users ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin - All Users | eAuto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/a2e8b6ea55.js" crossorigin="anonymous"></script>
  <link rel="icon" href="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" type="image/x-icon" />
  <style>
    body {
      margin: 0;
      background-color: #f8f9fa;
    }

    .main-content {
      margin-left: 220px;
      padding: 40px;
    }

    h2 {
      color: #1b2c7a;
      font-weight: 600;
    }

    table th {
      background: #1b2c7a;
      color: white;
    }

    table tr:hover {
      background-color: #f1f1f1;
    }

    .form-control {
      max-width: 300px;
    }

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
        padding: 20px;
      }

      .form-control {
        width: 100%;
      }

      .d-flex {
        flex-direction: column;
        gap: 10px;
      }
    }
  </style>
</head>
<body>

<?php include 'admin-sidebar.php'; ?>

<div class="main-content">
  <h2 class="mb-4" data-aos="fade-right">Registered Users</h2>

  <!-- Search Bar -->
  <form class="d-flex mb-4" method="get" data-aos="fade-down">
    <input type="text" name="search" class="form-control me-2" placeholder="Search by name or email" value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-primary"><i class="fas fa-search me-1"></i>Search</button>
  </form>

  <!-- Users Table -->
  <div class="table-responsive" data-aos="fade-up">
    <table class="table table-bordered align-middle table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Contact</th>
          <th>Birthdate</th>
          <th>Gender</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['fullname']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['contact']) ?></td>
              <td><?= $row['birthdate'] ? date('d M Y', strtotime($row['birthdate'])) : '-' ?></td>
              <td>
                <span class="badge bg-<?= $row['gender'] === 'Male' ? 'primary' : ($row['gender'] === 'Female' ? 'danger' : 'secondary') ?>">
                  <?= $row['gender'] ?>
                </span>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center">No users found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({ duration: 600, once: true });</script>

</body>
</html>
