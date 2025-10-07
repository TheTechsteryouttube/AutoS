<?php
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $model_id = $_POST['model_id'];
    $production_year = $_POST['production_year'];
    $engine_cc = $_POST['engine_cc'];
    $bhp = $_POST['bhp'];
    $torque = $_POST['torque'];
    $fuel_type = $_POST['fuel_type'];
    $seat_height = $_POST['seat_height'];
    $discontinued_year = $_POST['discontinued_year'];
    $price = $_POST['price'];
    $mileage = $_POST['mileage'];

    // Fetch company + model name for folder structure
    $res = $con->query("SELECT m.model_name, c.company_name 
                        FROM models m 
                        JOIN companies c ON m.company_id = c.company_id 
                        WHERE m.model_id = '$model_id'");
    $data = $res->fetch_assoc();
    $companyName = preg_replace('/[^A-Za-z0-9]/', '_', $data['company_name']);
    $modelName   = preg_replace('/[^A-Za-z0-9]/', '_', $data['model_name']);

    // Upload path
    $uploadDir = "../uploads/$companyName/$modelName/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES['variant_image']['name']);
    $targetFile = $uploadDir . $fileName;
    $check = $con->prepare("SELECT COUNT(*) as cnt 
                            FROM model_variants 
                            WHERE model_id = ? AND production_year = ? AND discontinued_year = ?");
    $check->bind_param("iii", $model_id, $production_year, $discontinued_year);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();
    $check->close();

    if ($result['cnt'] > 0) {
        // Already exists
        echo "<script>alert('Model with same production year and discontinued year already exists!');window.location='admin_dash.php'</script>";
        exit;
    }
    if (move_uploaded_file($_FILES['variant_image']['tmp_name'], $targetFile)) {
        $relativePath = "uploads/$companyName/$modelName/$fileName";

        $stmt = $con->prepare("INSERT INTO model_variants 
            (model_id, production_year, engine_cc, bhp, torque, fuel_type, seat_height, discontinued_year, price, mileage, variant_image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iidsssiddss", $model_id, $production_year, $engine_cc, $bhp, $torque, $fuel_type, $seat_height, $discontinued_year, $price, $mileage, $relativePath);

        if ($stmt->execute()) {
            echo "<script>alert('Model Added Succesfully');window.location='admin_dash.php'</script>";
        } else {
            echo "<script>alert('Error. $stmt->error');window.location='admin_dash.php'</script>";
        }
        $stmt->close();
    } else {
        echo "Image upload failed.";
    }
}
?>
