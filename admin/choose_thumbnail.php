<?php
$video = isset($_GET['video']) ? $_GET['video'] : '';
if (!$video) {
    die("❌ No video provided.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Choose Thumbnail</title>
<style>
.video-container { max-width: 600px; margin: auto; text-align: center; }
.thumbnail-preview { margin-top: 20px; }
button { margin: 10px; padding: 10px 20px; }
</style>
</head>
<body>

<div class="video-container">
    <h2>Pick a Thumbnail</h2>
    <video id="video" width="600" controls>
        <source src="<?= htmlspecialchars($video) ?>" type="video/mp4">
        Your browser does not support video playback.
    </video>
    <br>
    <button onclick="captureFrame()">📸 Capture Frame</button>
    <input type="file" id="customThumb" accept="image/*">

    <div class="thumbnail-preview">
        <h3>Preview:</h3>
        <canvas id="canvas" width="600" height="338"></canvas>
    </div>

    <button onclick="saveThumbnail()">✅ Save Thumbnail</button>
</div>

<script>
function captureFrame() {
    let video = document.getElementById("video");
    let canvas = document.getElementById("canvas");
    let ctx = canvas.getContext("2d");
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
}

function saveThumbnail() {
    let canvas = document.getElementById("canvas");
    let dataURL = canvas.toDataURL("image/png");

    fetch("save_thumbnail.php", {
        method: "POST",
        body: JSON.stringify({ 
            video: "<?= htmlspecialchars($video) ?>", 
            thumbnail: dataURL 
        }),
        headers: { "Content-Type": "application/json" }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            window.location.href = "admin_dash.php"; // go back to admin dashboard
        }
    });
}
</script>

</body>
</html>
