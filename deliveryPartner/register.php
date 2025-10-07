<?php
include("../includes/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $con->prepare("INSERT INTO delivery_partner (name, email, phone, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $password);
    if ($stmt->execute()) {
        header("Location: login.php?msg=Registered successfully");
        exit;
    } else {
        $error = "Error: Email already exists.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Partner Registration | AutoStream</title>
<link rel="stylesheet" href="../assets/css/delivery_auth.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-box">
        <h1>Partner Sign Up</h1>
        <p class="subtitle">Join AutoStream Deliveries</p>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST">
            <div class="input-group">
                <input type="text" name="name" placeholder="Name" required>
            </div>
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <input type="text" name="phone" placeholder="Phone" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>

        <p class="switch">Already have an account? <a href="login.php">Login</a></p>
    </div>
</div>
</body>
</html>
