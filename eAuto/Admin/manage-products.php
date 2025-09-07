<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$conn = new mysqli("localhost", "root", "", "eauto");

// Add Product
if (isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $original_price = $_POST['original_price'];
    $discount_amount = $_POST['discount_amount'];
    $image_name = $_POST['image'];
    $brand = $_POST['brand'];
    $main_brand = $_POST['main_brand'];
    $rating = $_POST['rating'];
    $reviews = $_POST['reviews'];
    $stock_status = $_POST['stock_status'];

    // Create brand folder if not exists
    $folder_path = "uploads/" . $main_brand;
    if (!file_exists($folder_path)) {
        mkdir($folder_path, 0777, true);
    }
    $image_path = $folder_path . "/" . basename($image_name);

    // Save full image path to DB (with Admin prefix)
    $image_db_path = "Admin/" . $image_path;

    $stmt = $conn->prepare("INSERT INTO products (product_name, price, original_price, discount_amount, image, brand, main_brand, rating, reviews, stock_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdddssssds", $product_name, $price, $original_price, $discount_amount, $image_db_path, $brand, $main_brand, $rating, $reviews, $stock_status);
    $stmt->execute();
    header("Location: manage-products.php");
    exit();
}

// Filters
$filter_brand = $_GET['main_brand'] ?? '';
$filter_stock = $_GET['stock_status'] ?? '';
$where = [];
if (!empty($filter_brand)) $where[] = "main_brand = '" . $conn->real_escape_string($filter_brand) . "'";
if (!empty($filter_stock)) $where[] = "stock_status = '" . $conn->real_escape_string($filter_stock) . "'";
$where_clause = $where ? "WHERE " . implode(" AND ", $where) : "";
$products = $conn->query("SELECT * FROM products $where_clause ORDER BY id Desc");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Products - eAuto Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a2e8b6ea55.js" crossorigin="anonymous"></script>
  <link rel="icon" href="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" type="image/x-icon" />
  <style>
    html, body {
      height: 100%;
      margin: 0;
      background-color: #f8f9fa;
    }

    .wrapper {
      display: flex;
      min-height: 100vh;
    }

    .main-content {
      flex-grow: 1;
      overflow-y: auto;
      padding: 20px;
      margin-left: 220px;
    }

    img.product-image {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 4px;
      box-shadow: 0 0 4px rgba(0,0,0,0.1);
    }

    .card {
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    .table th, .table td {
      vertical-align: middle;
    }

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

<div class="wrapper">
  <?php include 'admin-sidebar.php'; ?>

  <div class="main-content">
    <h3 class="mb-4" data-aos="fade-right">Manage Products</h3>

    <!-- Filter -->
    <form method="GET" class="row g-2 mb-4" data-aos="fade-up">
      <div class="col-md-3">
        <select name="main_brand" class="form-select">
          <option value="">All Brands</option>
          <option value="BRAND B" <?= $filter_brand == 'BRAND B' ? 'selected' : '' ?>>BRAND B</option>
          <option value="Hero" <?= $filter_brand == 'Hero' ? 'selected' : '' ?>>Hero</option>
          <option value="Honda" <?= $filter_brand == 'Honda' ? 'selected' : '' ?>>Honda</option>
        </select>
      </div>
      <div class="col-md-3">
        <select name="stock_status" class="form-select">
          <option value="">All Stock Status</option>
          <option value="In Stock" <?= $filter_stock == 'In Stock' ? 'selected' : '' ?>>In Stock</option>
          <option value="Sold Out" <?= $filter_stock == 'Sold Out' ? 'selected' : '' ?>>Sold Out</option>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
      </div>
      <div class="col-md-2">
        <a href="manage-products.php" class="btn btn-secondary w-100"><i class="fas fa-times me-1"></i> Reset</a>
      </div>
    </form>

    <!-- Add Product Form -->
    <div class="card mb-4" data-aos="fade-up">
      <div class="card-header">Add New Product</div>
      <div class="card-body">
        <form method="POST">
          <div class="row g-3">
            <div class="col-md-4"><input type="text" name="product_name" class="form-control" placeholder="Product Name" required></div>
            <div class="col-md-2"><input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required></div>
            <div class="col-md-2"><input type="number" step="0.01" name="original_price" class="form-control" placeholder="Original Price"></div>
            <div class="col-md-2"><input type="number" step="0.01" name="discount_amount" class="form-control" placeholder="Discount"></div>
            <div class="col-md-2"><input type="text" name="image" class="form-control" placeholder="Image filename"></div>
            <div class="col-md-3"><input type="text" name="brand" class="form-control" placeholder="Brand" required></div>
            <div class="col-md-3">
              <select name="main_brand" class="form-select" required>
                <option value="">Select Main Brand</option>
                <option value="BRAND B">BRAND B</option>
                <option value="Hero">Hero</option>
                <option value="Honda">Honda</option>
              </select>
            </div>
            <div class="col-md-2"><input type="number" step="0.1" name="rating" class="form-control" placeholder="Rating"></div>
            <div class="col-md-2"><input type="number" name="reviews" class="form-control" placeholder="Reviews"></div>
            <div class="col-md-2">
              <select name="stock_status" class="form-select">
                <option value="In Stock">In Stock</option>
                <option value="Sold Out">Sold Out</option>
              </select>
            </div>
            <div class="col-md-2">
              <button type="submit" name="add_product" class="btn btn-success w-100">Add Product</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Product Table -->
    <div class="card" data-aos="fade-up" data-aos-delay="200">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Product List</span>
        <a href="export-products.php<?= !empty($where_clause) ? '?' . http_build_query($_GET) : '' ?>" class="btn btn-sm btn-success">
          <i class="fas fa-file-excel me-1"></i> Export to Excel
        </a>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-striped mb-0">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Image</th>
              <th>Name</th>
              <th>Brand</th>
              <th>Main Brand</th>
              <th>Price</th>
              <th>Original</th>
              <th>Discount</th>
              <th>Rating</th>
              <th>Reviews</th>
              <th>Stock</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $products->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td>
                <?php if ($row['image']): ?>
                  <img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="product-image" alt="<?= htmlspecialchars($row['product_name']) ?>">
                <?php else: ?>
                  <span class="text-muted">No image</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($row['product_name']) ?></td>
              <td><?= htmlspecialchars($row['brand']) ?></td>
              <td><?= htmlspecialchars($row['main_brand']) ?></td>
              <td>₹<?= number_format($row['price'], 2) ?></td>
              <td><?= $row['original_price'] ? '₹' . number_format($row['original_price'], 2) : '-' ?></td>
              <td><?= $row['discount_amount'] ? '₹' . number_format($row['discount_amount'], 2) : '-' ?></td>
              <td><?= $row['rating'] ?? '-' ?></td>
              <td><?= $row['reviews'] ?? '-' ?></td>
              <td><span class="<?= $row['stock_status'] === 'In Stock' ? 'text-success' : 'text-danger' ?>"><?= $row['stock_status'] ?></span></td>
              <td>
                <a href="edit-product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="delete-product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?');">Delete</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration: 600, once: true });
</script>
</body>
</html>
