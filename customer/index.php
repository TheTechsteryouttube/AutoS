<?php
$currentPage='home';
include("header.php"); // Ensure path is correct
include("../includes/db_connect.php"); // Ensure path is correct

// Start session if not already started in header.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
$user_id = $_SESSION['user_id'] ?? null;
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

// Use prepared statements to prevent SQL injection
if (!empty($search)) {
    // Normalize input for searching
    $normalizedSearch = '%' . preg_replace('/[\s-]+/', '', strtolower($search)) . '%';
    
    $sql .= " WHERE REPLACE(REPLACE(LOWER(m.model_name), ' ', ''), '-', '') LIKE ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $normalizedSearch);
} else {
    $sql .= " ORDER BY m.model_id, v.production_year DESC";
    $stmt = $con->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AutoStream - Find Your Bike</title>
  
  <style>
    /* --- 1. SETUP & FONT IMPORT --- */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    :root {
      --bg-dark-primary: #0d0d0d;
      --bg-dark-secondary: #1a1a1a;
      --accent-red: #e50914;
      --accent-red-hover: #f40612;
      --text-primary: #ffffff;
      --text-secondary: #b3b3b3;
      --border-color: #2a2a2a;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background: var(--bg-dark-primary);
      color: var(--text-primary);
      font-family: 'Poppins', sans-serif;
    }

    /* --- 2. GRID & CARDS --- */
    .grid-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 30px;
      padding: 30px;
      max-width: 1600px;
      margin: 0 auto;
    }

    .card {
      background: var(--bg-dark-secondary);
      border-radius: 16px;
      overflow: hidden;
      border: 1px solid var(--border-color);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
      display: flex;
      flex-direction: column;
    }

    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0px 10px 25px rgba(229, 9, 20, 0.5);
    }

    .card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .card-content {
      padding: 20px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }
    
    .card-content h3 {
      margin: 0 0 10px 0;
      color: var(--text-primary);
      font-size: 1.3rem;
      font-weight: 600;
    }

    .variant-specs {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-bottom: 20px;
        line-height: 1.6;
    }
    
    .spec-item {
        display: block; /* Each on a new line */
    }

    .spec-item strong {
        color: var(--text-primary);
        font-weight: 500;
    }
    
    .card-footer {
        margin-top: auto; /* Pushes footer to the bottom */
        border-top: 1px solid var(--border-color);
        padding-top: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .price {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--accent-red);
    }

    .year-badge {
        background-color: var(--accent-red);
        color: var(--text-primary);
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .no-results {
      text-align: center;
      padding: 80px 20px;
      color: #999;
      font-size: 1.2rem;
      grid-column: 1 / -1; /* Span full grid width */
    }
  </style>
</head>
<body>

  <main class="grid-container">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card" onclick="window.location.href='model_details.php?variant_id=<?php echo $row['variant_id']; ?>&user_id=<?php echo $user_id; ?>'">
            <img src="../<?php echo htmlspecialchars($row['variant_image']); ?>" alt="<?php echo htmlspecialchars($row['model_name']); ?>">
            <div class="card-content">
              <h3><?php echo htmlspecialchars($row['model_name']); ?></h3>
              <div class="variant-specs">
                  <span class="spec-item"><strong>Engine:</strong> <?php echo htmlspecialchars($row['engine_cc']); ?>cc</span>
                  <span class="spec-item"><strong>Power:</strong> <?php echo htmlspecialchars($row['bhp']); ?> BHP</span>
                  <span class="spec-item"><strong>Mileage:</strong> <?php echo htmlspecialchars($row['mileage']); ?> kmpl</span>
              </div>
              <div class="card-footer">
                  <span class="price">₹<?php echo number_format($row['price']); ?></span>
                  <span class="year-badge"><?php echo htmlspecialchars($row['production_year']); ?></span>
              </div>
            </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="no-results">
        <?php if (!empty($search)): ?>
          Could not find any models matching "<strong><?php echo htmlspecialchars($search); ?></strong>"
        <?php else: ?>
          No models are available at the moment. Please check back later.
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </main>

</body>
</html>