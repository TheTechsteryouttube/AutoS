<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

include '../includes/db_connect.php'; //database connection
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists
    $check = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) == 0) {
        echo "Email not found in our system.";
        exit;
    }

    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['reset_email'] = $email;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'midhunem670@gmail.com';
        $mail->Password   = 'knqh afst mzio tguv';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('midhunem670@gmail.com', 'AutoStream');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'AutoStream Password Reset OTP';
        $mail->Body    = "Your OTP for resetting password is: <b>$otp</b>";

        $mail->send();
        header("Location: verifyOtp.php");
        exit;
    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
    }
}
?>
