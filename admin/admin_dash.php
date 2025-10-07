<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>AutoStream Admin Panel</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- jQuery globally for all subpages -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
  <div class="sidebar">
    <h2>AutoStream</h2>
    <a href="#" onclick="loadPage('fetchUsers.php', this)"><i class="fa-solid fa-users"></i>  Registered Users</a>
    <a href="#" onclick="loadPage('delivery_partners.php', this)"><i class="fa-solid fa-users"></i>  Delivery Partners</a>
    <!--<a href="#" onclick="loadPage('upload_form.php', this)"><i class="fa-solid fa-video"></i>  Upload  Videos</a>-->
    <a href="#" onclick="loadPage('company_dashboard.php', this)"><i class="fa-solid fa-building"></i>  Company Details</a>
    <div class="dropdown">
        <button class="dropdown-btn"><i class="fa-solid fa-cart-shopping"></i> Spares </button>
        <div class="dropdown-content">
          <a href='#' onclick="loadPage('addSpare.php', this)"><i class="fa-solid fa-video"></i>Add Spares</a>
          <a href="#" onclick="loadPage('bookings.php', this)"><i class="fa-solid fa-cart-shopping"></i>  Bookings</a>
        </div>
    </div>
    <div class="dropdown">
        <button class="dropdown-btn"><i class="fa-solid fa-video"></i> Videos </button>
        <div class="dropdown-content">
            <a href="#" onclick="loadPage('upload_form.php', this)"><i class="fa-solid fa-video"></i>  Upload  Videos</a>
            <a href="#" onclick="loadPage('uploaded.php', this)"><i class="fa-solid fa-video"></i>Uploaded Videos</a>
        </div>
    </div>
    <div class="dropdown">
        <button class="dropdown-btn"><i class="fa-solid fa-motorcycle"></i> Model Details </button>
        <div class="dropdown-content">
            <a href="#" onclick="loadPage('viewModel.php', this)"><i class="fa-solid fa-table"></i>View Models</a>
            <a href="#" onclick="loadPage('addModel.php', this)"><i class="fa-regular fa-plus"></i>Add Model</a>
            <a href="#" onclick="loadPage('addModelVariant.php', this)"><i class="fa-regular fa-plus"></i>Add Model-Variant</a>
        </div>
    </div>
    <div class="logout"><a href='logout.php' >Log Out</a></div>
  </div>
  
  <div class="main">
    <div id="users" class="section active">
      <h3>Welcome Admin..!</h3>
      <p>List of users will be shown here from the database.</p>
    </div>

    <div id="bookings" class="section">
      <h3>Spare Part Bookings</h3>
      <p>Bookings will be dynamically loaded here.</p>
    </div>

    <div id="upload" class="section">
      <h3>Upload Bike Video</h3>
      <form action="upload_video.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Video Title" required />
        <input type="file" name="video_file" accept="video/*" required />
        <textarea name="description" placeholder="Video Description"></textarea>
        <button type="submit">Upload</button>
      </form>
    </div>

    <div id="company" class="section">
      <h3>Add Company</h3>
      <form action="add_company.php" method="POST">
        <input type="text" name="company_name" placeholder="Company Name" required />
        <button type="submit">Add Company</button>
      </form>
    </div>

    <div id="models" class="section">
      <h3>Add Model</h3>
      <form action="add_model.php" method="POST">
        <input type="text" name="model_name" placeholder="Model Name" required />
        <select name="company_id" required>
          <option value="">Select Company</option>
          <!-- Populate using PHP -->
        </select>
        <button type="submit">Add Model</button>
      </form>
    </div>

    <div id="edit" class="section">
      <h3>Edit Models/Companies</h3>
      <p>Implement editing and deletion options here.</p>
    </div>
  </div>
<script>
  function loadPage(page, linkElement) {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", page, true);

  xhr.onload = function () {
    if (xhr.status === 200) {
      const mainDiv = document.querySelector(".main");
      mainDiv.innerHTML = xhr.responseText;

      // Execute any scripts from loaded HTML
      mainDiv.querySelectorAll("script").forEach(script => {
        const newScript = document.createElement("script");
        if (script.src) {
          newScript.src = script.src;
        } else {
          newScript.textContent = script.textContent;
        }
        document.body.appendChild(newScript);
      });

      // Update active class in sidebar
      const links = document.querySelectorAll(".sidebar a");
      links.forEach(link => link.classList.remove("active"));
      linkElement.classList.add("active");
    } else {
      document.querySelector(".main").innerHTML = "<p>Error loading content.</p>";
    }
  };
  xhr.send();
}

</script>
</script>
<script src="../assets/js/company.js"></script>
<script>document.querySelectorAll(".dropdown-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    const parent = btn.parentElement;
    parent.classList.toggle("active");
  });
});  
</script>
</body>
</html>
