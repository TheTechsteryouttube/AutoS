<?php
include '../includes/db_connect.php';

if (isset($_GET['variant_id'])) {
    $variant_id = intval($_GET['variant_id']);

    $stmt = $con->prepare("
        SELECT v.id, v.title, v.description, v.filepath, v.thumbnail_path, 
               mv.production_year, m.model_name, c.company_name
        FROM videos v
        JOIN model_variants mv ON v.variant_id = mv.variant_id
        JOIN models m ON mv.model_id = m.model_id
        JOIN companies c ON m.company_id = c.company_id
        WHERE v.variant_id = ?
        ORDER BY v.created_at DESC
    ");
    $stmt->bind_param("i", $variant_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($video = $result->fetch_assoc()) {
            $videoPath = "../" . $video['filepath'];
            $thumbPath = "../" . $video['thumbnail_path'];

            echo "
            <div class='card'>
                <video controls width='100%' poster='" . htmlspecialchars($thumbPath) . "'>
                    <source src='" . htmlspecialchars($videoPath) . "' type='video/mp4'>
                    Your browser does not support the video tag.
                </video>
                <h3>" . htmlspecialchars($video['title']) . "</h3>
                <p>" . htmlspecialchars($video['description']) . "</p>
                <small>
                    " . htmlspecialchars($video['company_name']) . " - " . 
                    htmlspecialchars($video['model_name']) . " (" . 
                    htmlspecialchars($video['production_year']) . ")
                </small>
            </div>";
        }
    } else {
        echo "<p style='text-align:center;'>No videos found for this variant.</p>";
    }

    $stmt->close();
}
?>
