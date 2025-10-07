<?php
include("includes/db_connect.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["user"];
    $password = $_POST["pass"];

    $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Check hashed password
        if (password_verify($password, $row["password"])) 
        {
            $_SESSION["user_id"] = $row["user_id"];
            header("Location:customer/index.php");
            exit;
        } else {
            echo "<script>alert('Invalid password!'); window.location='customerLogin.php';</script>";
        }
    } else {
        echo "<script>alert('Username not found!'); window.location='customerLogin.php';</script>";
    }

    $stmt->close();
    $con->close();
}
?>
