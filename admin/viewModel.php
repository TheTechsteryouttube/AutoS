<html>
<link rel="stylesheet" href="../assets/css/model.css">
</html>
<?php
include '../includes/db_connect.php';
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Query to join models and model_variants
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

$result = $con->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>View Models</title>
</head>
<body>

<h2>Model Details</h2>
<div class="table-container">
<table>
    <tr>
        <th>Model Name</th>
        <th>Production Year</th>
        <th>Engine CC</th>
        <th>BHP</th>
        <th>Torque</th>
        <th>Fuel Type</th>
        <th>Seat Height</th>
        <th>Discontinued Year</th>
        <th>Price</th>
        <th>Mileage</th>
        <th>Image</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . htmlspecialchars($row['model_name']) . "</td>
                <td>" . $row['production_year'] . "</td>
                <td>" . $row['engine_cc'] . "</td>
                <td>" . $row['bhp'] . "</td>
                <td>" . $row['torque'] . "</td>
                <td>" . $row['fuel_type'] . "</td>
                <td>" . $row['seat_height'] . "</td>
                <td>" . $row['discontinued_year'] . "</td>
                <td>" . $row['price'] . "</td>
                <td>" . $row['mileage'] . "</td>
                <td><img src='../" .$row['variant_image'] ."' alt='Variant Image' width='80' height='80'></td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='11'>No models found.</td></tr>";
    }
    ?>
</table>
</div>
</body>
</html>
