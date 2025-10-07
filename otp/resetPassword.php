<?php
include '../includes/db_connect.php';
session_start();

if (!isset($_SESSION['reset_email'])) {
    echo "Unauthorized access.";
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['reset_email'];
    $newpass = $_POST['new_password'];
    $hashed = password_hash($newpass, PASSWORD_DEFAULT);

    $update = mysqli_query($con, "UPDATE users SET password='$hashed' WHERE email='$email'");

    if ($update) {
        $message = "Password reset successful!";
        echo "<script>alert('Password reset successfully');window.location='../CustomerLogin.php'</script>";
        session_destroy(); // Clear OTP session
    } else {
        $message = "Failed to reset password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="../assets/css/style.css"> <!-- Your main style -->
  <style>
    .reset-container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    .reset-box {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 30px;
        width: 400px;
        text-align: center;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
    }

    .reset-box header {
        color: #fff;
        font-size: 26px;
        margin-bottom: 20px;
    }

    .reset-box input[type="password"] {
        font-size: 15px;
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        height: 50px;
        width: 100%;
        padding: 0 15px;
        border: none;
        border-radius: 10px;
        outline: none;
        margin-bottom: 20px;
        transition: .2s ease;
    }

    .reset-box input[type="password"]:hover,
    .reset-box input[type="password"]:focus {
        background: rgba(255, 255, 255, 0.25);
    }

    .reset-box input[type="submit"] {
        font-size: 15px;
        font-weight: 500;
        color: black;
        height: 45px;
        width: 100%;
        border: none;
        border-radius: 30px;
        outline: none;
        background: rgba(255, 255, 255, 0.7);
        cursor: pointer;
        transition: .3s ease-in-out;
    }

    .reset-box input[type="submit"]:hover {
        background: rgba(255, 255, 255, 0.5);
        box-shadow: 1px 5px 7px 1px rgba(0, 0, 0, 0.2);
    }

    .message {
        color: #fff;
        margin-bottom: 15px;
        font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="reset-container">
      <form class="reset-box" method="post">
        <header>Reset Password</header>
        <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>
        <input type="password" name="new_password" placeholder="Enter New Password" required>
        <input type="submit" value="Reset Password">
      </form>
    </div>
  </div>
</body>
</html>

