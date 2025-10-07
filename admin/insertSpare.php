<?php
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model_id    = intval($_POST['model_id']);
    $spare_name  = trim($_POST['spare_name']);
    $price       = floatval($_POST['price']);
    $stock       = intval($_POST['stock']);
    $description = trim($_POST['description']);

    // ✅ Fetch company + model name from DB
    $query = $con->prepare("
        SELECT m.model_name, c.company_name 
        FROM models m 
        JOIN companies c ON m.company_id = c.company_id
        WHERE m.model_id = ?
    ");
    $query->bind_param("i", $model_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 0) {
        die("Invalid model selected.");
    }

    $row        = $result->fetch_assoc();
    $companyName= preg_replace('/[^a-zA-Z0-9_\-]/', '_', $row['company_name']);
    $modelName  = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $row['model_name']);

    // ✅ Directory: uploads/company/model/spares
    $upload_dir = "../uploads/" . $companyName . "/" . $modelName . "/spares/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // ✅ Handle image upload
    $image_name  = time() . "_" . basename($_FILES['spare_image']['name']);
    $target_file = $upload_dir . $image_name;

    if (move_uploaded_file($_FILES['spare_image']['tmp_name'], $target_file)) {
        // Save relative path
        $image_path = "uploads/" . $companyName . "/" . $modelName . "/spares/" . $image_name;

        // ✅ Insert into DB
        $stmt = $con->prepare("INSERT INTO spares (model_id, spare_name, price, description, stock, image_path) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isdiss", $model_id, $spare_name, $price, $description, $stock, $image_path);

        if ($stmt->execute()) {
            echo "<script>alert('Spare added successfully!'); window.location.href='admin_dash.php?section=addSpare';</script>";
        } else {
            echo "Database error: " . $stmt->error;
        }
    } else {
        echo "Failed to upload image.";
    }
}
?>
