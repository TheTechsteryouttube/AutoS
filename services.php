<?php
$currentPage = 'services';
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Our Services - AutoStream</title>
  <link rel="stylesheet" href="assets/css/services.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <header class="header">
    <h1>Our Services</h1>
    <p>Everything you need for motorcycles – in one place</p>
    <a href="index.php"><i class="fa-solid fa-house"></i></a>
  </header>

  <section class="services-container">
    <!-- Service 1 -->
    <div class="service-card">
      <i class="fa-solid fa-motorcycle"></i>
      <h2>View Motorcycles</h2>
      <p>Explore different motorcycles from top brands with images and details.</p>
    </div>

    <!-- Service 2 -->
    <div class="service-card">
      <i class="fa-solid fa-list"></i>
      <h2>See Specifications</h2>
      <p>Check technical specifications like engine, mileage, torque, and more.</p>
    </div>

    <!-- Service 3 -->
    <div class="service-card">
      <i class="fa-solid fa-video"></i>
      <h2>Watch Videos</h2>
      <p>View detailed review and demo videos of motorcycles to make better decisions.</p>
    </div>

    <!-- Service 4 -->
    <div class="service-card">
      <i class="fa-solid fa-cogs"></i>
      <h2>Spare Parts</h2>
      <p>Find genuine spare parts for motorcycles, with compatibility checks.</p>
    </div>

    <!-- Service 5 -->
    <div class="service-card">
      <i class="fa-solid fa-cart-shopping"></i>
      <h2>Book Spare Parts</h2>
      <p>Order spare parts online for specific motorcycles with easy booking system.</p>
    </div>
  </section>
</body>
</html>
