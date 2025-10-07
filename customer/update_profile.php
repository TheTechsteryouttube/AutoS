<?php
session_start();
include("../includes/db_connect.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location:../ login.php");
    exit;
}

// Check if POST data is set
if (!isset($_POST['user_id'], $_POST['name'], $_POST['email'])) {
    die("Invalid request.");
}

$user_id = intval($_POST['user_id']);
$name = trim($_POST['name']);
$email = trim($_POST['email']);

// Simple validation
if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Please provide valid Name and Email.");
}

// Update user in database
$stmt = $con->prepare("UPDATE users SET name = ?, email = ? WHERE user_id = ?");
$stmt->bind_param("ssi", $name, $email, $user_id);

if ($stmt->execute()) {
    $_SESSION['success_msg'] = "Profile updated successfully!";
    header("Location: profile.php");
    exit;
} else {
    die("Error updating profile. Please try again.");
}
?>
