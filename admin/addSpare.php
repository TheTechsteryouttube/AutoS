<?php
include '../includes/db_connect.php';

// Fetch models for dropdown
$models = $con->query("
    SELECT m.model_id, m.model_name, c.company_name 
    FROM models m 
    JOIN companies c ON m.company_id = c.company_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Spare - AutoStream Admin</title>
  <link rel="stylesheet" href="../assets/css/addSpare.css">
</head>
<body>
  <div class="add-spare-container">
    <h3>Add Spare</h3>
    <form action="insertSpare.php" method="POST" enctype="multipart/form-data">
      
      <!-- Select model -->
      <label for="model_id">Select Model</label>
      <select name="model_id" id="model_id" required>
        <option value="">-- Select Model --</option>
        <?php while($row = $models->fetch_assoc()): ?>
          <option value="<?= $row['model_id']; ?>">
            <?= $row['company_name'] . " - " . $row['model_name']; ?>
          </option>
        <?php endwhile; ?>
      </select>

      <!-- Spare name -->
      <label for="spare_name">Spare Name</label>
      <input type="text" name="spare_name" id="spare_name" required>

      <!-- Price -->
      <label for="price">Price</label>
      <input type="number" step="0.01" name="price" id="price" required>

      <!-- Stock -->
      <label for="stock">In Stock</label>
      <input type="number" name="stock" id="stock" required>

      <!-- Description -->
      <label for="description">Description</label>
      <textarea name="description" id="description" rows="4"></textarea>

      <!-- Image upload -->
      <label for="spare_image">Upload Image</label>
      <input type="file" name="spare_image" id="spare_image" accept="image/*" required>

      <button type="submit">Add Spare</button>
    </form>
  </div>
</body>
</html>
