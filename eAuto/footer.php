<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>eAuto | Footer</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- AOS Animation CSS -->
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <style>
    a.text-info:hover {
      text-decoration: underline;
      color: #17c1e8 !important;
    }
    footer p, footer li {
      font-size: 14px;
    }
  </style>
</head>
<body>

<!-- FOOTER -->
<footer class="bg-dark text-light pt-5 pb-4">
  <div class="container">
    <!-- Row with 3 Columns -->
    <div class="row text-center text-md-start">
      <!-- About -->
      <div class="col-md-4 mb-4" data-aos="fade-up">
        <h5 class="text-uppercase fw-bold mb-3">About Us</h5>
        <p>
          eAuto is your one-stop shop for genuine two-wheeler spare parts.
          We deliver top-quality products to meet your automotive needs with unmatched convenience and reliability.
        </p>
      </div>

      <!-- Contact -->
      <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
        <h5 class="text-uppercase fw-bold mb-3">Contact Us</h5>
        <ul class="list-unstyled">
          <li>Email: <a href="mailto:support@eauto.com" class="text-info text-decoration-none">support@eauto.com</a></li>
          <li>Phone: +91 98765 43210</li>
          <li>Address: 101 Auto Street, Gujarat, India</li>
        </ul>
      </div>

      <!-- Reviews -->
      <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
        <h5 class="text-uppercase fw-bold mb-3">Customer Reviews</h5>
        <p>"Fast delivery and authentic parts! Highly recommended." <br><small>- Ankit Patel</small></p>
        <p>"Saved 10% on my prepaid order, love it!" <br><small>- Priya Desai</small></p>
      </div>
    </div>

    <!-- Bottom Row -->
    <div class="row">
      <div class="col text-center mt-3">
        <small>&copy; 2025 <strong>eAuto</strong>. All Rights Reserved.</small>
      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS Animation JS -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ once: true });
</script>

</body>
</html>