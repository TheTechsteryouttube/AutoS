<?php
$currentPage='home';
include("header.php");
include("includes/db_connect.php");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$search = isset($_GET['search']) ? trim($_GET['search']) : "";

$sql = " 
    SELECT 
        m.model_id,
        m.model_name,
        v.variant_id,
        v.production_year,
        v.engine_cc,
        v.bhp,
        v.torque,
        v.fuel_type,
        v.seat_height,
        v.discontinued_year,
        v.price,
        v.mileage,
        v.variant_image
    FROM models m
    INNER JOIN model_variants v ON m.model_id = v.model_id
";

if (!empty($search)) {
    $searchEscaped = $con->real_escape_string($search);
    $sql .= " WHERE m.model_name LIKE '%$searchEscaped%' OR v.production_year LIKE '%$searchEscaped%'";
}

$sql .= " ORDER BY m.model_id, v.production_year DESC";

$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AutoStream - Models</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #0d0d0d;
      color: #fff;
    }

    .grid-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      padding: 30px;
      justify-content: center;
    }

    .card {
      flex: 0 0 250px;
      max-width: 250px;
      background: #1a1a1a;
      border-radius: 10px;
      border: 1px solid #333;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      padding: 15px;
      text-align: center;
      cursor: pointer;
    }

    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0px 6px 15px rgba(229, 9, 20, 0.4);
    }

    .card img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 6px;
      margin: 10px auto;
      display: block;
    }

    .card-content h3 {
      margin: 8px 0;
      color:rgb(255, 255, 255);
      font-size: 1rem;
    }

    .variant-details {
      font-size: 0.8rem;
      color: #ccc;
      text-align: left;
      margin-top: 10px;
    }

    .variant-details strong {
      color: #e50914;
    }

    .no-results {
      text-align: center;
      padding: 50px;
      color: #999;
    }

    /* Popup Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.7);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: #1a1a1a;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      width: 300px;
      box-shadow: 0px 6px 15px rgba(229, 9, 20, 0.5);
    }

    .modal-content h2 {
      color: #e50914;
      margin-bottom: 15px;
    }

    .modal-content p {
      margin-bottom: 20px;
      color: #ccc;
    }

    .modal-content button {
      padding: 10px 20px;
      background: #e50914;
      border: none;
      border-radius: 6px;
      color: white;
      font-size: 1rem;
      cursor: pointer;
    }

    .modal-content button:hover {
      background: #b20710;
    }
  </style>
</head>
<body>

  <section class="grid-container">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card" onclick="showLoginPopup()">
          <?php 
            // Fix image path (adjust based on your folder structure)
            $imagePath = "../" . ltrim($row['variant_image'], "/"); 
          ?>
          <img src="<?php echo htmlspecialchars($row['variant_image']); ?>" alt="<?php echo htmlspecialchars($row['model_name']); ?>">
          <div class="card-content">
            <h3><?php echo htmlspecialchars($row['model_name']); ?> (<?php echo htmlspecialchars($row['production_year']); ?>)</h3>
            <div class="variant-details">
              <!--<strong>Engine:</strong> <?php echo htmlspecialchars($row['engine_cc']); ?>cc<br>
              <strong>BHP:</strong> <?php echo htmlspecialchars($row['bhp']); ?><br>
              <strong>Torque:</strong> <?php echo htmlspecialchars($row['torque']); ?><br>
              <strong>Fuel:</strong> <?php echo htmlspecialchars($row['fuel_type']); ?><br>
              <strong>Mileage:</strong> <?php echo htmlspecialchars($row['mileage']); ?><br>
              <strong>Price:</strong> ₹<?php echo number_format($row['price']); ?>-->
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="no-results">
        <?php if (!empty($search)): ?>
          No models found for "<strong><?php echo htmlspecialchars($search); ?></strong>"
        <?php else: ?>
          No models available right now.
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </section>

  <!-- Modal Popup -->
  <div id="loginModal" class="modal">
    <div class="modal-content">
      <h2>Login Required</h2>
      <p>You need to login to continue.</p>
      <button onclick="window.location.href='CustomerLogin.php'">Login</button>
    </div>
  </div>

  <script>
    function showLoginPopup() {
      document.getElementById('loginModal').style.display = 'flex';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
      const modal = document.getElementById('loginModal');
      if (event.target === modal) {
        modal.style.display = "none";
      }
    }
  </script>

</body>
</html>
