<?php
include("../includes/db_connect.php");

$response = ["success" => false, "message" => "Invalid request"];

if (isset($_POST['action'], $_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);

    if ($_POST['action'] === 'update_status' && isset($_POST['status'])) {
        $status = $_POST['status'];
        $stmt = $con->prepare("UPDATE bookings SET delivery_status = ? WHERE booking_id = ?");
        $stmt->bind_param("si", $status, $booking_id);
        $response["success"] = $stmt->execute();
        $response["message"] = "Status updated successfully!";
    }

    if ($_POST['action'] === 'update_location' && isset($_POST['location'])) {
        $location = $_POST['location'];
        $stmt = $con->prepare("UPDATE bookings SET current_location = ? WHERE booking_id = ?");
        $stmt->bind_param("si", $location, $booking_id);
        $response["success"] = $stmt->execute();
        $response["message"] = "Location updated successfully!";
    }
}

header("Content-Type: application/json");
echo json_encode($response);
