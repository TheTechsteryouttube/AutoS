<?php
$currentPage="";
// session_start(); // Uncomment if you use sessions for user login
include("header.php"); // Make sure this path is correct
include("../includes/db_connect.php"); // Make sure this path is correct

if (!isset($_GET['variant_id']) || empty($_GET['variant_id'])) {
    die("Invalid request.");
}

$variant_id = intval($_GET['variant_id']);

// Assuming user_id might come from session or GET for different use cases
if (isset($_SESSION['user_id'])) {
    $user_id = intval($_SESSION['user_id']);
} elseif (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
} else {
    $user_id = 0; // Default to 0 or handle as a guest user
}


// Fetch bike + model info
$stmt = $con->prepare("
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
    FROM model_variants v
    JOIN models m ON v.model_id = m.model_id
    WHERE v.variant_id = ?
");
$stmt->bind_param("i", $variant_id);
$stmt->execute();
$result = $stmt->get_result();
$bike = $result->fetch_assoc();

if (!$bike) {
    die("No details found for this bike.");
}

// Fetch related videos
$stmt2 = $con->prepare("
    SELECT id, title, thumbnail_path 
    FROM videos 
    WHERE variant_id=?
");
$stmt2->bind_param("i", $variant_id);
$stmt2->execute();
$videos = $stmt2->get_result();

// Fetch related spares (using model_id)
$stmt3 = $con->prepare("
    SELECT spare_id, spare_name, price, stock, image_path 
    FROM spares 
    WHERE model_id=?
");
$stmt3->bind_param("i", $bike['model_id']);
$stmt3->execute();
$spares = $stmt3->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($bike['model_name']); ?> - AutoStream</title>
  
  <style>
    /* --- 1. SETUP & FONT IMPORT --- */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    :root {
      --bg-dark-primary: #0d0d0d;
      --bg-dark-secondary: #1a1a1a;
      --bg-dark-tertiary: #212121;
      --accent-red: #e50914;
      --accent-red-hover: #f40612;
      --text-primary: #ffffff;
      --text-secondary: #b3b3b3;
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
      overflow-x: hidden; /* Prevent horizontal scroll */
    }

    /* Custom Scrollbar for a more integrated look */
    ::-webkit-scrollbar {
      width: 8px;
    }
    ::-webkit-scrollbar-track {
      background: var(--bg-dark-secondary);
    }
    ::-webkit-scrollbar-thumb {
      background: var(--accent-red);
      border-radius: 4px;
    }

    /* --- 2. MAIN LAYOUT & CONTAINER --- */
    .container {
      max-width: 1400px;
      margin: 40px auto;
      padding: 0 20px;
      display: flex;
      flex-wrap: wrap; /* Allows stacking on smaller screens */
      gap: 30px;
    }

    .main-content {
      flex: 3;
      min-width: 65%;
    }

    .spares-sidebar {
      flex: 1;
      min-width: 300px;
    }

    /* --- 3. BIKE HEADER & INFO TABLE --- */
    .bike-header {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 40px;
      background: var(--bg-dark-secondary);
      padding: 30px;
      border-radius: 16px;
      border: 1px solid #222;
      box-shadow: 0 8px 30px rgba(0,0,0,0.5);
    }

    .bike-header img {
      flex-shrink: 0;
      width: 40%;
      max-width: 450px;
      height: auto;
      border-radius: 12px;
      object-fit: cover;
      box-shadow: 0 10px 25px rgba(0,0,0,0.7);
    }

    .bike-info {
      flex-grow: 1;
    }
    
    .bike-info h2 {
      font-size: 2.5rem; /* Larger, more impactful title */
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 25px;
      line-height: 1.2;
    }
    
    .bike-info h2 span {
        color: var(--accent-red); /* Highlight the year */
    }

    .bike-info table {
      width: 100%;
      border-collapse: collapse;
      font-size: 1rem;
    }

    .bike-info th, .bike-info td {
      text-align: left;
      padding: 12px 0;
      border-bottom: 1px solid #333; /* Separator lines */
    }

    .bike-info th {
      color: var(--text-secondary);
      font-weight: 400;
      width: 150px;
    }

    .bike-info td {
      font-weight: 600;
      color: var(--text-primary);
    }

    /* --- 4. SECTION STYLING (VIDEOS & SPARES) --- */
    .section-title {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 3px solid var(--accent-red);
        display: inline-block;
    }

    .videos {
      margin-top: 40px;
    }
    
    .video-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
    }

    .card { /* A base card style for both videos and spares */
        background: var(--bg-dark-secondary);
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        border: 1px solid #222;
    }

    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(229, 9, 20, 0.4);
    }

    .video-card .card-image-container {
        position: relative;
    }
    
    .video-card img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      display: block;
    }
    
    /* Play icon overlay on hover */
    .video-card .card-image-container::after {
        content: '▶';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 40px;
        color: white;
        background: rgba(0,0,0,0.5);
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .video-card:hover .card-image-container::after {
        opacity: 1;
    }

    .card-content {
        padding: 15px;
    }

    .card-content p {
      margin: 0;
      font-size: 1rem;
      font-weight: 500;
    }

    /* --- 5. SPARES SIDEBAR --- */
    .spares-sidebar {
      background: var(--bg-dark-secondary);
      padding: 20px;
      border-radius: 16px;
      max-height: 90vh; /* Limit height */
      overflow-y: auto;
      border: 1px solid #222;
    }

    .spare-card img {
      width: 100%;
      height: 150px;
      object-fit: contain; /* Use contain for product images */
      padding: 10px;
      background: var(--bg-dark-tertiary);
    }

    .spare-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }

    .spare-info .price {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--accent-red);
    }
    
    .spare-info .stock {
        font-size: 0.9rem;
        color: var(--text-secondary);
        background: var(--bg-dark-primary);
        padding: 4px 8px;
        border-radius: 6px;
    }

    .no-results {
        color: var(--text-secondary);
        padding: 20px;
        text-align: center;
    }
    
    /* --- 6. RESPONSIVE DESIGN --- */
    @media (max-width: 1024px) {
        .bike-header {
            flex-direction: column;
            text-align: center;
        }
        .bike-header img {
            width: 80%;
            max-width: 400px;
        }
        .bike-info h2 {
            font-size: 2rem;
        }
    }
    
    @media (max-width: 768px) {
        .container {
            flex-direction: column;
        }
        .main-content, .spares-sidebar {
            min-width: 100%;
        }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="main-content">
      <div class="bike-header">
        <img src="../<?php echo htmlspecialchars($bike['variant_image']); ?>" alt="<?php echo htmlspecialchars($bike['model_name']); ?>">
        <div class="bike-info">
          <h2><?php echo htmlspecialchars($bike['model_name']); ?> <span>(<?php echo htmlspecialchars($bike['production_year']); ?>)</span></h2>
          <table>
            <tr><th>Engine</th><td><?php echo htmlspecialchars($bike['engine_cc']); ?> cc</td></tr>
            <tr><th>BHP</th><td><?php echo htmlspecialchars($bike['bhp']); ?></td></tr>
            <tr><th>Torque</th><td><?php echo htmlspecialchars($bike['torque']); ?></td></tr>
            <tr><th>Mileage</th><td><?php echo htmlspecialchars($bike['mileage']); ?> kmpl</td></tr>
            <tr><th>Seat Height</th><td><?php echo htmlspecialchars($bike['seat_height']); ?> mm</td></tr>
            <tr><th>Price</th><td>₹ <?php echo number_format($bike['price']); ?></td></tr>
            <?php if (!empty($bike['discontinued_year'])): ?>
            <tr><th>Discontinued</th><td><?php echo htmlspecialchars($bike['discontinued_year']); ?></td></tr>
            <?php endif; ?>
          </table>
        </div>
      </div>

      <div class="videos">
        <h3 class="section-title">Related Videos</h3>
        <div class="video-grid">
          <?php if ($videos->num_rows > 0): ?>
            <?php while ($video = $videos->fetch_assoc()): ?>
              <div class="card video-card" onclick="window.location.href='video_player.php?id=<?php echo $video['id']; ?>'">
                  <div class="card-image-container">
                    <img src="../<?php echo htmlspecialchars($video['thumbnail_path']); ?>" alt="<?php echo htmlspecialchars($video['title']); ?>">
                  </div>
                  <div class="card-content">
                    <p><?php echo htmlspecialchars($video['title']); ?></p>
                  </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <p class="no-results">No videos available for this model yet.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="spares-sidebar">
      <h3 class="section-title">Available Spares</h3>
      <?php if ($spares->num_rows > 0): ?>
        <?php while ($spare = $spares->fetch_assoc()): ?>
          <div class="card spare-card" onclick="window.location.href='spare_details.php?spare_id=<?php echo urlencode($spare['spare_id']); ?>&user_id=<?php echo $user_id; ?>'">
            <img src="../<?php echo htmlspecialchars($spare['image_path']); ?>" alt="<?php echo htmlspecialchars($spare['spare_name']); ?>">
            <div class="card-content">
              <p><?php echo htmlspecialchars($spare['spare_name']); ?></p>
              <div class="spare-info">
                  <span class="price">₹<?php echo number_format($spare['price']); ?></span>
                  <span class="stock">Stock: <?php echo htmlspecialchars($spare['stock']); ?></span>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="no-results">No spares available for this bike yet.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>