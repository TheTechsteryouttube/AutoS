<?php
include '../includes/db_connect.php';

// Always respond in JSON
header('Content-Type: application/json');

// Validate required fields
$variant_id = isset($_POST['variant_id']) ? intval($_POST['variant_id']) : 0;
$title       = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';

//get model id
$model_id_Query = $con->prepare("SELECT model_id FROM model_variants WHERE variant_id=?");
$model_id_Query->bind_param("i",$variant_id);
$model_id_Query->execute();
$result = $model_id_Query->get_result();

if ($result && $row = $result->fetch_assoc()) {
    $model_id = (int)$row['model_id'];
} else {
    $model_id = 0;
}

if ($model_id == 0 || empty(trim($title))) {
    echo json_encode(["status" => "error", "message" => "❌ Model and Title are required."]);
    exit;
}

// Validate video file upload
if (!isset($_FILES['video']) || $_FILES['video']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["status" => "error", "message" => "❌ Please upload a valid video file."]);
    exit;
}

// Check video file type
$allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];
if (!in_array($_FILES['video']['type'], $allowedTypes)) {
    echo json_encode(["status" => "error", "message" => "❌ Only MP4, WEBM, and OGG videos allowed."]);
    exit;
}

// Check file size (max 200MB)
if ($_FILES['video']['size'] > 200 * 1024 * 1024) {
    echo json_encode(["status" => "error", "message" => "❌ File too large. Max 200MB allowed."]);
    exit;
}

// Get model name
$modelQuery = $con->prepare("SELECT model_name FROM models WHERE model_id = ?");
$modelQuery->bind_param("i", $model_id);
$modelQuery->execute();
$modelResult = $modelQuery->get_result();

if ($modelResult->num_rows == 0) {
    echo json_encode(["status" => "error", "message" => "❌ Invalid model selected."]);
    exit;
}

$modelRow  = $modelResult->fetch_assoc();
$modelName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $modelRow['model_name']); // safe folder name

/* ---------------- VIDEO UPLOAD ---------------- */
$videoRelDir = "uploads/videos/" . $modelName . "/";
$videoAbsDir = "../" . $videoRelDir;

if (!is_dir($videoAbsDir)) {
    mkdir($videoAbsDir, 0777, true);
}

$videoName = time() . "_" . basename($_FILES['video']['name']);
$videoRelPath = $videoRelDir . $videoName;  
$videoAbsPath = $videoAbsDir . $videoName;  

if (!move_uploaded_file($_FILES['video']['tmp_name'], $videoAbsPath)) {
    echo json_encode(["status" => "error", "message" => "❌ Failed to upload video."]);
    exit;
}

/* ---------------- THUMBNAIL UPLOAD ---------------- */
$thumbnailRelPath = null; // default if no thumbnail uploaded

if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
    $allowedImgTypes = ['image/jpeg', 'image/png', 'image/webp'];

    if (in_array($_FILES['thumbnail']['type'], $allowedImgTypes)) {
        $thumbRelDir = "uploads/thumbnails/" . $modelName . "/";
        $thumbAbsDir = "../" . $thumbRelDir;

        if (!is_dir($thumbAbsDir)) {
            mkdir($thumbAbsDir, 0777, true);
        }

        $thumbName = time() . "_" . basename($_FILES['thumbnail']['name']);
        $thumbnailRelPath = $thumbRelDir . $thumbName;  
        $thumbAbsPath = $thumbAbsDir . $thumbName;  

        if (!move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbAbsPath)) {
            $thumbnailRelPath = null; // fallback if move fails
        }
    }
}

/* ---------------- DATABASE INSERT ---------------- */
$stmt = $con->prepare("INSERT INTO videos (variant_id, title, description, filepath, thumbnail_path) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $variant_id, $title, $description, $videoRelPath, $thumbnailRelPath);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "✅ Video & thumbnail uploaded successfully!",
        "video"   => $videoRelPath,
        "thumbnail" => $thumbnailRelPath
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "❌ Database error: " . $con->error]);
}
$stmt->close();
?>
