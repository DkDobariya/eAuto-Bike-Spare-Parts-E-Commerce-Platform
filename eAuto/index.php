<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>eAuto | Bike Model</title>

  <!-- Bootstrap & AOS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link rel="icon" href="https://cdn.shopify.com/s/files/1/0415/7846/3390/files/eauto-app-logo_x320.jpg?v=1631018743" type="image/x-icon" />
  <style>
    body {
      padding-top: 100px;
      font-family: Arial, sans-serif;
    }

    .hero-section {
      position: relative;
      height: 570px;
      color: white;
      overflow: hidden;
    }

    .carousel-item,
    .hero-slide {
      height: 100%;
      background-size: cover;
      background-position: center;
    }

    .hero-overlay {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.4);
      z-index: 1;
    }

    .hero-content {
      position: absolute;
      z-index: 2;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .btn-shop {
      background-color: #ff7f00;
      color: #fff;
      font-weight: bold;
      padding: 10px 30px;
      font-size: 16px;
      text-transform: uppercase;
      border-radius: 5px;
      box-shadow: 0 4px 12px rgba(255, 127, 0, 0.5);
      transition: background-color 0.3s;
      text-decoration: none;
    }

    .btn-shop:hover {
      background-color: #e66300;
    }

    .bike-card {
      background: #fff;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .bike-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    .bike-image {
      width: 100%;
      height: auto;
      border-radius: 8px;
      margin-bottom: 12px;
    }

    .brand-name {
      font-weight: 600;
      font-size: 18px;
      color: #1b2c7a;
    }

    .arrow {
      margin-left: 6px;
      font-size: 16px;
    }

    @media (max-width: 768px) {
      .hero-section {
        height: 400px;
      }
    }
  </style>
</head>

<?php include 'header.php'; ?>

<body>

<!-- HERO SECTION -->
<section class="hero-section">
  <div id="heroCarousel" class="carousel slide carousel-fade h-100" data-bs-ride="carousel" data-bs-interval="3500">
    <div class="carousel-inner h-100">
      <div class="carousel-item active">
        <div class="hero-slide" style="background-image: url('img/main.jpg');"></div>
      </div>
      <div class="carousel-item">
        <div class="hero-slide" style="background-image: url('img/main2.jpg');"></div>
      </div>
    </div>
  </div>
  <div class="hero-overlay"></div>
  <div class="hero-content text-center">
    <a href="#brands" class="btn-shop" aria-label="Shop Now">Shop Now</a>
  </div>
</section>

<!-- BIKE BRANDS -->
<div class="container my-5" id="brands">
  <div class="row g-4 justify-content-center">

    <!-- Bajaj -->
    <div class="col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
      <a href="Bajaj.php" class="text-decoration-none">
        <div class="bike-card">
          <img src="img/Bike/Bajaj.jpg" alt="Bajaj Bike" class="bike-image" loading="lazy">
          <div class="brand-name">Bajaj <span class="arrow">→</span></div>
        </div>
      </a>
    </div>

    <!-- Hero -->
    <div class="col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
      <a href="Hero.php" class="text-decoration-none">
        <div class="bike-card">
          <img src="img/Bike/Hero.jpg" alt="Hero Bike" class="bike-image" loading="lazy">
          <div class="brand-name">Hero <span class="arrow">→</span></div>
        </div>
      </a>
    </div>

    <!-- Honda -->
    <div class="col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
      <a href="Honda.php" class="text-decoration-none">
        <div class="bike-card">
          <img src="img/Bike/Honda.jpg" alt="Honda Bike" class="bike-image" loading="lazy">
          <div class="brand-name">Honda <span class="arrow">→</span></div>
        </div>
      </a>
    </div>

  </div>
</div>

<!-- Bootstrap & AOS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration: 1000, once: true });
</script>

</body>
</html>

<?php include 'footer.php'; ?>
