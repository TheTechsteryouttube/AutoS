<?php
include '../includes/db_connect.php';

// Return JSON always
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model_id = $_POST['model_id'] ?? null;


    // 🔹 Fetch model name for directory
    $model_query = $con->prepare("SELECT model_name FROM models WHERE model_id = ?");
    $model_query->bind_param("i", $model_id);
    $model_query->execute();
    $model_query->bind_result($model_name);
    $model_query->fetch();
    $model_query->close();

    if (!$model_name) {
        echo json_encode(["status" => "error", "message" => "Invalid model selected."]);
        exit;
    }

    // 🔹 Build directory path
    $baseDir = "../uploads/companies/";
    $modelDir = $baseDir . $model_name . "/";

    if (!is_dir($modelDir)) {
        if (!mkdir($modelDir, 0777, true)) {
            echo json_encode(["status" => "error", "message" => "Failed to create directory."]);
            exit;
        }
    }

    $image_path = "";

    // 🔹 Handle image upload
    if (isset($_FILES['variant_image']) && $_FILES['variant_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['variant_image']['tmp_name'];
        $file_name = basename($_FILES['variant_image']['name']);
        $target_path = $modelDir . $file_name;

        if (move_uploaded_file($file_tmp, $target_path)) {
            $image_path = "uploads/companies/" . $model_name . "/" . $file_name;
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to move uploaded file."]);
            exit;
        }
    }

    // 🔹 Insert into database
    $stmt = $con->prepare("INSERT INTO model_variants (model_id, variant_name, image) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $model_id, $variant_name, $image_path);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Variant added successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database insert failed: " . $stmt->error]);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
