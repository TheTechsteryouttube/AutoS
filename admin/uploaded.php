<?php
include '../includes/db_connect.php';

// Fetch variants with model + company + year
$variants = $con->query("
    SELECT v.variant_id, v.production_year, m.model_name, c.company_name 
    FROM model_variants v
    JOIN models m ON v.model_id = m.model_id
    JOIN companies c ON m.company_id = c.company_id
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Uploaded Videos</title>
<link rel="stylesheet" href="../assets/css/uploaded.css">
<!-- ✅ Reliable jQuery CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <h2>Uploaded Videos</h2>

    <form id="variantForm">
        <label for="variant">Select Variant To View Videos: </label>
        <select name="variant_id" id="variant">
            <option value="">-- Select --</option>
            <?php while ($row = $variants->fetch_assoc()): ?>
                <option value="<?= $row['variant_id'] ?>">
                    <?= htmlspecialchars($row['company_name'] . ' - ' . $row['model_name'] . ' (' . $row['production_year'] . ')') ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <div id="videoGrid" class="grid-container"></div>

<script>
$(document).ready(function(){
    $("#variant").on("change", function(){
        var variant_id = $(this).val();
        if(variant_id){
            $.ajax({
                url: "fetch_videos.php",
                type: "GET",
                data: { variant_id: variant_id }, // ✅ send variant_id instead of model_id
                success: function(data){
                    $("#videoGrid").html(data);
                },
                error: function(xhr, status, error){
                    console.error("AJAX Error:", xhr.responseText);
                    $("#videoGrid").html("<p style='color:red; text-align:center;'>Failed to load videos.</p>");
                }
            });
        } else {
            $("#videoGrid").html("<p style='text-align:center;'></p>");
        }
    });
});
</script>

<script>
function toggleMenu(btn) {
    let menu = btn.parentElement;
    menu.classList.toggle("show");
    // close other menus
    document.querySelectorAll(".menu").forEach(m => {
        if (m !== menu) m.classList.remove("show");
    });
}

// Edit video (load edit form dynamically)
function editVideo(videoId) {
    $.ajax({
        url: "edit_video_form.php",
        type: "GET",
        data: { video_id: videoId },
        success: function(data){
            $(".main").html(data); // load form inside dashboard main area
        },
        error: function(xhr){
            alert("Failed to load edit form: " + xhr.responseText);
        }
    });
}

// Delete video
function deleteVideo(videoId, variantId) {
    if (!confirm("Are you sure you want to delete this video?")) return;

    $.ajax({
        url: "delete_video.php",
        type: "POST",
        data: { video_id: videoId },
        success: function(res){
            alert(res);
            // reload video list for same variant
            $.get("fetch_videos.php", { variant_id: variantId }, function(data){
                $("#videoGrid").html(data);
            });
        },
        error: function(xhr){
            alert("Failed to delete video: " + xhr.responseText);
        }
    });
}
</script>
</body>
</html>
