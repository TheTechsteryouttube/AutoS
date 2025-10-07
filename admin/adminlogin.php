<?php
session_start();
include '../includes/db_connect.php'; // Update path if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['user']);
    $password = $_POST['pass'];

    // Fetch admin details from the database
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            // Correct login
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['username'];
            header("Location: admin_dash.php");
            exit();
        } else {
            echo "<script>alert('Invalid password'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid username'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
