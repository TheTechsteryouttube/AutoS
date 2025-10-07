<?php
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $model_name = trim($_POST['model_name']);
    $company_id = $_POST['company_id'];

    $check = "SELECT * FROM models WHERE model_name='$model_name'";
    $result = $con->query($check);
    if( $result->num_rows > 0) {
        echo "<script>alert('Model Already exists');window.location='admin_dash.php'</script>";	
        exit();
    }
    else
    {
    $sql = "INSERT INTO models (model_name, company_id) VALUES ('$model_name', '$company_id')";
    if ($con->query($sql)) {
        echo "<script>alert('Model Added Succesfully');window.location='admin_dash.php'</script>";	
    } else {
        echo "<script>alert('Error: " . $con->error . "');window.location='admin_dash.php'</script>";
    }
}
}
?>
