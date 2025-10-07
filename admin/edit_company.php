<?php
include '../includes/db_connect.php';
if (isset($_POST['id']) && isset($_POST['name'])) {
  $id = (int) $_POST['id'];
  $name = mysqli_real_escape_string($con, $_POST['name']);
  mysqli_query($con, "UPDATE companies SET company_name = '$name' WHERE company_id = $id");
}
