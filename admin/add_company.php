<?php
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['company_name']) && !empty(trim($_POST['company_name']))) {
        
        $name = mysqli_real_escape_string($con, $_POST['company_name']);
        $insert = mysqli_query($con, "INSERT INTO companies (company_name) VALUES ('$name')");

        if ($insert) {
            http_response_code(200);
            echo "success";
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => mysqli_error($con)]);
        }

    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Company name is required"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>