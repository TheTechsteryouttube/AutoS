<?php
session_start();
include("../includes/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT * FROM delivery_partner WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $partner = $result->fetch_assoc();
        if (password_verify($password, $partner['password'])) {
            $_SESSION['partner_id'] = $partner['id'];
            $_SESSION['partner_name'] = $partner['name'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Email not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Partner Login | AutoStream</title>
<link rel="stylesheet" href="../assets/css/delivery_auth.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-box">
        <h1>Partner Login</h1>
        <p class="subtitle">Access your deliveries</p>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST">
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>

        <p class="switch">Don’t have an account? <a href="register.php">Register here</a></p>
    </div>
</div>
</body>
</html>
