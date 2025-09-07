<?php
$conn = new mysqli("localhost", "root", "", "eauto");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$order = $conn->query("SELECT * FROM orders WHERE id = $order_id")->fetch_assoc();
if (!$order) {
    die("Order not found.");
}

$items = [];
$grand_total = 0;
$result = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
while ($row = $result->fetch_assoc()) {
    $row['total'] = $row['quantity'] * $row['price'];
    $grand_total += $row['total'];
    $items[] = $row;
}

$barcode_data = "ORDER-" . str_pad($order_id, 6, "0", STR_PAD_LEFT);
$qr_data = $order['fullname'] . ", " . $order['address'] . ", ₹" . $order['total_amount'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shipping Label</title>
  <link href="https://fonts.googleapis.com/css2?family=Patrick+Hand&display=swap" rel="stylesheet">
  <link rel="icon" href="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" type="image/x-icon" />
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      padding: 2rem;
      margin: 0;
      background-color: #f8f9fa;
      animation: fadeIn 1s ease-in;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(20px);}
      to {opacity: 1; transform: translateY(0);}
    }

    h2 {
      text-align: center;
      font-size: 1.8rem;
      margin-bottom: 20px;
    }

    h2 span {
      color: #f68b1e;
      font-weight: bold;
    }

    .label-box {
      border: 2px solid #000;
      padding: 20px;
      max-width: 600px;
      margin: 0 auto;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      animation: zoomIn 0.5s ease;
    }

    @keyframes zoomIn {
      from {transform: scale(0.95); opacity: 0;}
      to {transform: scale(1); opacity: 1;}
    }

    .top {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .top .order {
      font-weight: bold;
      font-size: 1.2rem;
    }

    .barcode, .qr {
      display: inline-block;
      margin-top: 10px;
    }

    table {
      width: 100%;
      font-size: 0.95rem;
      margin-top: 12px;
      border-collapse: collapse;
    }

    th, td {
      padding: 6px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
    }

    .products {
      margin-top: 20px;
    }

    .total-line {
      font-weight: bold;
      text-align: right;
    }

    .brand {
      margin-top: 20px;
      text-align: right;
    }

    @media (max-width: 600px) {
      .top {
        flex-direction: column;
        align-items: flex-start;
      }

      .qr img {
        margin-top: 10px;
      }

      .label-box {
        padding: 15px;
      }
    }
  </style>
</head>
<body>

  <h2><span>Shipping Label</span></h2>

  <div class="label-box">
    <div class="top">
      <div class="order">Order: <?php echo str_pad($order_id, 6, "0", STR_PAD_LEFT); ?></div>
      <div class="qr">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=<?php echo urlencode($qr_data); ?>" alt="QR Code">
      </div>
    </div>

    <div class="barcode">
      <img src="https://barcode.tec-it.com/barcode.ashx?data=<?php echo urlencode($barcode_data); ?>&code=Code128&dpi=96" alt="Barcode">
    </div>

    <table>
      <tr><td><strong>Name:</strong></td><td><?php echo htmlspecialchars($order['fullname']); ?></td></tr>
      <tr><td><strong>Phone:</strong></td><td><?php echo htmlspecialchars($order['phone']); ?></td></tr>
      <tr><td><strong>Address:</strong></td><td><?php echo htmlspecialchars($order['address']); ?></td></tr>
      <tr><td><strong>City:</strong></td><td><?php echo htmlspecialchars($order['city']); ?></td></tr>
      <tr><td><strong>State / ZIP:</strong></td><td><?php echo htmlspecialchars($order['state']) . " - " . htmlspecialchars($order['zip']); ?></td></tr>
      <tr><td><strong>Order Total:</strong></td><td>₹<?php echo htmlspecialchars($order['total_amount']); ?></td></tr>
    </table>

    <div class="products">
      <strong>Products:</strong>
      <table>
        <tr>
          <th>Product</th>
          <th>Qty</th>
          <th>Price</th>
          <th>Total</th>
        </tr>
        <?php foreach ($items as $item): ?>
          <tr>
            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
            <td><?php echo (int)$item['quantity']; ?></td>
            <td>₹<?php echo number_format($item['price'], 2); ?></td>
            <td>₹<?php echo number_format($item['total'], 2); ?></td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="3" class="total-line">Grand Total</td>
          <td><strong>₹<?php echo number_format($grand_total, 2); ?></strong></td>
        </tr>
      </table>
    </div>

    <div class="brand">
      <div class="text-center my-3">
        <a href="#" target="_blank" rel="noopener noreferrer">
        <img 
            src="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" 
            alt="eAuto Logo" 
            width="80" 
            height="80" 
            style="border-radius: 8px;"
        >
        </a>
      </div>
    </div>
  </div>
  <div id="print-note" style="text-align: center; margin: 20px; color: #dc3545; font-weight: bold;">
  📌 Note: Please check printer settings before continuing.
</div>

<script>
  window.addEventListener('load', function () {
    alert("⚠️ Make sure your printer is connected and has paper.");
    setTimeout(() => {
      window.print();
    }, 500); // small delay to let images or fonts load
  });
</script>

<style media="print">
  #print-note {
    display: none;
  }
</style>


</body>
</html>
