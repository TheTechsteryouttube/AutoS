<?php
include '../includes/db_connect.php';
$variants = $con->query("
    SELECT v.variant_id, v.production_year,m.model_id, m.model_name, c.company_name
    FROM model_variants v
    JOIN models m ON v.model_id = m.model_id
    JOIN companies c ON m.company_id = c.company_id
    ORDER BY c.company_name, m.model_name, v.production_year
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Upload Video - AutoStream Admin</title>
<link rel="stylesheet" href="../assets/css/upload.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<form id="uploadForm" method="POST" enctype="multipart/form-data" >
  <h2>Upload Video</h2>

  <label for="variant_id">Select Variant:</label>
  <select name="variant_id" required>
      <option value="">-- Select Variant --</option>
      <?php while($row = $variants->fetch_assoc()): ?>
          <option value="<?= $row['variant_id']?>">
              <?= htmlspecialchars($row['company_name']) ?> - 
              <?= htmlspecialchars($row['model_name']) ?> 
              (<?= htmlspecialchars($row['production_year']) ?>)
          </option>
      <?php endwhile; ?>
  </select><br><br>

  <label>Video Title:</label>
  <input type="text" name="title" required>

  <label>Description:</label>
  <textarea name="description" placeholder="Enter video description..."></textarea>

  <label>Upload Video:</label>
  <input type="file" name="video" accept="video/*" required>

  <label>Upload Thumbnail:</label>
  <input type="file" name="thumbnail" accept="image/*" required>

  <button type="submit">Upload</button>

  <div class="progress-container">
    <div id="progressBar"></div>
  </div>
</form>

<!-- Toasts -->
<div id="toast-container"></div>

<script>
/* --- Toast Function --- */
function showToast(message, type = "info") {
    const container = document.getElementById("toast-container");

    const toast = document.createElement("div");
    toast.className = "toast " + type;
    toast.innerHTML = `
        ${message}
        <span class="close-btn">&times;</span>
    `;

    toast.querySelector(".close-btn").onclick = () => {
        toast.classList.remove("show");
        setTimeout(() => toast.remove(), 300);
    };

    container.appendChild(toast);

    // animate in
    setTimeout(() => toast.classList.add("show"), 100);

    // auto remove
    setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

/* --- Form Submit --- */
document.getElementById("uploadForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let formData = new FormData(this);
    let xhr = new XMLHttpRequest();

    xhr.open("POST", "upload_video.php", true);

    // Progress Bar
    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            let percent = (e.loaded / e.total) * 100;
            document.getElementById("progressBar").style.width = percent + "%";
        }
    };

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                let response = JSON.parse(xhr.responseText);
                showToast(response.message, response.status);
                if (response.status === "success") {
                    document.getElementById("uploadForm").reset();
                    document.getElementById("progressBar").style.width = "0%";
                }
            } catch (err) {
                showToast("⚠️ Invalid server response", "error");
                console.log("Raw response:", xhr.responseText);
            }
        } else {
            showToast("❌ Upload failed.", "error");
        }
    };

    xhr.onerror = function() {
        showToast("⚠️ Network error during upload.", "error");
    };

    xhr.send(formData);
});
</script>
</body>
</html>