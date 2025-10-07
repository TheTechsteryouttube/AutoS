<?php
session_start();
if (isset($_SESSION['partner_id'])) {
   // header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Delivery Partner | AutoStream</title>
<link rel="stylesheet" href="../assets/css/delivery_home.css">
</head>
<body>

<div class="container">
    <div class="left">
        <div class="overlay"></div>
        <video autoplay muted loop class="bg-video">
            <source src="videos/delivery_intro.mp4" type="video/mp4">
        </video>
        <div class="video-text">
            <h1>Become a Delivery Partner</h1>
            <p>Join AutoStream’s trusted delivery network. Help customers receive their bike parts and accessories quickly and safely. Update delivery status, manage routes, and make deliveries seamless.</p>
        </div>
    </div>

    <div class="right">
        <h2>Welcome Delivery Partner</h2>
        <p>Login to manage deliveries or sign up to join our network.</p>

        <div class="btn-group">
            <a href="login.php" class="btn">Login</a>
            <a href="register.php" class="btn secondary">Sign Up</a>
        </div>

        <div class="info">
            <h3>Your Role</h3>
            <ul>
                <li>✔️ Update order status in real-time</li>
                <li>🚚 Add current delivery location</li>
                <li>📦 Ensure timely package delivery</li>
            </ul>
        </div>
    </div>
</div>

</body>
</html>
