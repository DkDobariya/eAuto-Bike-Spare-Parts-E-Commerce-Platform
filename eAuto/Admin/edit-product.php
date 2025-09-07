<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "eauto");

$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();

if (isset($_POST['update_product'])) {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $original_price = $_POST['original_price'];
    $discount_amount = $_POST['discount_amount'];
    $image = $_POST['image'];
    $brand = $_POST['brand'];
    $main_brand = $_POST['main_brand'];
    $rating = $_POST['rating'];
    $reviews = $_POST['reviews'];
    $stock_status = $_POST['stock_status'];

    $stmt = $conn->prepare("UPDATE products SET product_name=?, price=?, original_price=?, discount_amount=?, image=?, brand=?, main_brand=?, rating=?, reviews=?, stock_status=? WHERE id=?");
    $stmt->bind_param("sdddssssdsi", $product_name, $price, $original_price, $discount_amount, $image, $brand, $main_brand, $rating, $reviews, $stock_status, $id);
    $stmt->execute();
    header("Location: manage-products.php");
    exit();
}
?>

<!-- Edit Form HTML -->
<!DOCTYPE html>
<html>
<head>
  <title>Edit Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" href="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" type="image/x-icon" />
</head>
<body class="container mt-4">
  <h3>Edit Product</h3>
  <form method="POST">
    <div class="row g-3">
      <div class="col-md-4">
        <input type="text" name="product_name" class="form-control" value="<?= $product['product_name'] ?>" required>
      </div>
      <div class="col-md-2">
        <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price'] ?>" required>
      </div>
      <div class="col-md-2">
        <input type="number" step="0.01" name="original_price" class="form-control" value="<?= $product['original_price'] ?>">
      </div>
      <div class="col-md-2">
        <input type="number" step="0.01" name="discount_amount" class="form-control" value="<?= $product['discount_amount'] ?>">
      </div>
      <div class="col-md-2">
        <input type="text" name="image" class="form-control" value="<?= $product['image'] ?>">
      </div>
      <div class="col-md-3">
        <input type="text" name="brand" class="form-control" value="<?= $product['brand'] ?>" required>
      </div>
      <div class="col-md-3">
        <select name="main_brand" class="form-select" required>
          <option value="BRAND B" <?= $product['main_brand'] == 'BRAND B' ? 'selected' : '' ?>>BRAND B</option>
          <option value="Hero" <?= $product['main_brand'] == 'Hero' ? 'selected' : '' ?>>Hero</option>
          <option value="Honda" <?= $product['main_brand'] == 'Honda' ? 'selected' : '' ?>>Honda</option>
        </select>
      </div>
      <div class="col-md-2">
        <input type="number" step="0.1" name="rating" class="form-control" value="<?= $product['rating'] ?>">
      </div>
      <div class="col-md-2">
        <input type="number" name="reviews" class="form-control" value="<?= $product['reviews'] ?>">
      </div>
      <div class="col-md-2">
        <select name="stock_status" class="form-select">
          <option value="In Stock" <?= $product['stock_status'] == 'In Stock' ? 'selected' : '' ?>>In Stock</option>
          <option value="Sold Out" <?= $product['stock_status'] == 'Sold Out' ? 'selected' : '' ?>>Sold Out</option>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" name="update_product" class="btn btn-primary w-100">Update</button>
      </div>
    </div>
  </form>
</body>
</html>
