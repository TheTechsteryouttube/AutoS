<?php include '../includes/db_connect.php'; ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoStream</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form action="index.php" method="GET" id="searchForm">
    <div class="wrapper">
        <nav class="nav">
            <div class="nav-logo">
                <p>AutoStream</p>
            </div>
            <div class="search-container">
            <div class="nav-icons">
                <i class='bx bx-search' id="searchIcon"></i>
            </div>
            <!-- Search bar -->
            <div class="search-bar" id="searchBar">
                <input type="text" id="searchInput" placeholder="Search bikes..." name="search">
            </div>
            </div>
            <div class="nav-menu" id="navMenu">
                <ul>
                    <li><a href="index.php"  class="<?php echo ($currentPage == 'home') ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="blog.php" class="<?php echo ($currentPage == 'blog') ? 'active' : ''; ?>">Blog</a></li>
                    <li><a href="services.php" class="<?php echo ($currentPage == 'services') ? 'active' : ''; ?>">Services</a></li>
                    <li><a href="profile.php" class="<?php echo ($currentPage == 'profile') ? 'active' : ''; ?>">My Profile</a></li>
                    <li><a href="logout.php" class="link">Logout</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Search Results -->
    <div id="searchResults" class="grid-container"></div>
</form>
<script>
$(document).ready(function(){  
    // Toggle search bar
    $("#searchIcon").click(function(){
        $("#searchBar").toggleClass("active");
        $("#searchInput").focus();
    });

    // Live search
    $("#searchInput").on("keyup", function(){
        var query = $(this).val();
        if(query.length > 1){
            $.ajax({
                url: "search.php",
                type: "GET",
                data: {q: query},
                success: function(data){
                    $("#searchResults").html(data);
                }
            });
        } else {
            $("#searchResults").html("");
        }
    });
});
</script>
</body>
</html>
