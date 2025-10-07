<?php
include '../includes/db_connect.php';

// Fetch all models for dropdown
$models = $con->query("SELECT m.model_id, m.model_name, c.company_name 
                       FROM models m 
                       JOIN companies c ON m.company_id = c.company_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Model Variant</title>
    <link rel="stylesheet" href="../assets/css/addVariant.css">
</head>
<body>
    <div class="add-variant-container">
    <h2>Add Model Variant</h2>
    <form action="insert_variant.php" method="POST" enctype="multipart/form-data">
        <label for="model_id">Select Model:</label>
        <select name="model_id" required>
            <option value="">-- Select Model --</option>
            <?php while($row = $models->fetch_assoc()): ?>
                <option value="<?= $row['model_id'] ?>">
                    <?= $row['company_name'] ?> - <?= $row['model_name'] ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Production Year:</label>
        <input type="number" name="production_year" required><br><br>

        <label>Engine CC:</label>
        <input type="number" name="engine_cc" required><br><br>

        <label>BHP:</label>
        <input type="text" name="bhp" required><br><br>

        <label>Torque:</label>
        <input type="text" name="torque" required><br><br>

        <label>Fuel Type:</label>
        <input type="text" name="fuel_type" required><br><br>

        <label>Seat Height:</label>
        <input type="number" name="seat_height" required><br><br>

        <label>Discontinued Year:</label>
        <input type="number" name="discontinued_year"><br><br>

        <label>Price:</label>
        <input type="number" name="price" step="0.01" required><br><br>

        <label>Mileage:</label>
        <input type="text" name="mileage" required><br><br>

        <label>Variant Image:</label>
        <input type="file" name="variant_image" accept="image/*" required><br><br>

        <button type="submit">Add Variant</button>
    </form>
</div>
</body>
</html>
